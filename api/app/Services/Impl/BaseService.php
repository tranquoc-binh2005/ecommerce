<?php
namespace App\Services\Impl;
use App\Services\Interfaces\BaseServiceInterface;
use App\Traits\HasCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHook;
use App\Traits\HasRelation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;

abstract class BaseService implements BaseServiceInterface {

    use HasHook, HasRelation, HasCache;

    protected  $modelData;
    protected  $model;
    protected  $result;
    protected  $baseRepository;

    protected $fieldSearchs = ['name'];
    protected $simpleFilter = ['publish']; // hook
    protected $complexFilter = ['id']; // hook
    protected $dateFilter = ['created_at', 'updated_at'];

    protected $with = [];

    protected const PERPAGE = 15;

    public function __construct(
       $baseRepository
    ){
        $this->baseRepository = $baseRepository;
    }

    protected abstract function prepareModelData(Request $request): self;

    private function buildFilter(Request $request, array $filters = []): array {
        $conditions = [];
        if(count($filters)){
            foreach($filters as $key => $filter){
                if($request->has($filter)){
                    $conditions[$filter] = $request->{$filter};
                }
            }
        }
        return $conditions;
    }

    public function specifications($request): array{
        return [
            'type' => $request->type === 'all',
            'perpage' => $request->perpage ?? self::PERPAGE,
            'sort' => $request->sort ? explode(',', $request->sort) : ['id', 'desc'],
            'keyword' => [
                'q' => $request->keyword,
                'fields' => $this->fieldSearchs
            ],
            'filters' => [
                'simple' => $this->buildFilter($request, $this->simpleFilter),
                'complex' => $this->buildFilter($request, $this->complexFilter),
                'date' => $this->buildFilter($request, $this->dateFilter),
            ],
            'with' => $this->with
        ];
    }

    /** READ DATABASE */
    public function paginate(Request $request){
        try {
            $cacheKey = $this->getPaginationCacheKey($request->all());
            $lockKey = "lock:{$cacheKey}";
            return Cache::lock($lockKey, 10)->block(5, function() use ($request, $cacheKey){
                return Cache::tags(["{$this->cacheKeyPrefix}:collection"])->remember(
                    $cacheKey,
                    $this->cacheTTL,
                    function() use ($request){
                        $specifications = $this->specifications($request);
                        return $this->baseRepository->paginate($specifications);
                    }
                );
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /** READ DATABASE */
    public function show(int $id = 0): Model{
        $lockKey = "lock:{$this->getCacheKeyById($id)}";
        return Cache::lock($lockKey, 10)->block(5, function() use ($id){
            return $this->model = Cache::remember(
                $this->getCacheKeyById($id),
                $this->cacheTTL,
                function() use ($id){
                    try {
                        if(! $model = $this->baseRepository->findById($id)){
                            throw new ModelNotFoundException(Lang::get('message.not_found'));
                        }
                        return $model;
                    } catch (\Exception $e) {
                        $this->rollback();
                        throw $e;
                    }
                }
            );
        });
    }

    public function save(Request $request, ?int $id = null): Model{
        try {
            return $this->beginTransaction()
                        ->prepareModelData($request)
                        ->beforeSave()
                        ->saveModel($id)
                        ->handleRelations($request)
                        ->afterSave()
                        ->commit()
                        ->getResult();
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function destroy(int $id): bool{
        try {
            return $this->beginTransaction()
                        ->beforeDelete($id)
                        ->deleteModel()
                        ->afterDelete()
                        ->commit()
                        ->getResult();
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function bulkDelete(Request $request): bool {
        try {
            return $this->beginTransaction()
                        ->bulkDeleteModel($request->ids)
                        ->afterBulkDelete()
                        ->commit()
                        ->getResult();
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function attachOrDetach(Request $request, string $action = ''): bool | null{
        try {
            return $this->beginTransaction()
            ->actachOrDetachModelRelation($request, $action)
            ->afterDelete()
            ->commit()
            ->getResult();
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}
