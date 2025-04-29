<?php  
namespace App\Services\Impl\User;
use App\Services\Interfaces\User\UserCatalogueServiceInterface;
use App\Services\Impl\BaseService;
use App\Repositories\User\UserCatalogueRepository;
use Illuminate\Http\Request;


class UserCatalogueService extends BaseService implements UserCatalogueServiceInterface {
    
    private $repository;
    protected $cacheKeyPrefix;

    protected $fieldSearchs = ['name', 'canonical'];
    protected $simpleFilter = ['publish', 'user_id'];
    protected $with = ['users'];

    private const USER_CATALOGUES = 'user_catalogues';
    

    public function __construct(
        UserCatalogueRepository $repository
    ){
        $this->repository = $repository;
        $this->cacheKeyPrefix = self::USER_CATALOGUES;
        parent::__construct($repository);
    }


    protected function prepareModelData(Request $request) :self{

        return $this->initializeBasicData($request);
    }

    protected function initializeBasicData(Request $request): self {
        $fillable = $this->repository->getFillable();
        $this->modelData = $request->only($fillable);
        return $this;
    }



   
}
