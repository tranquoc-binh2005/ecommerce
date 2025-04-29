<?php 
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseRepository {

    protected $model;

    public function __construct(
        Model $model
    )
    {
        $this->model = $model;    
    }

    public function __call($name, $arguments)
    {
        $field = Str::snake(str_replace('findBy', '', $name));
        $value = $arguments[0];
        return $this->model->where($field, $value)->first();
    }

    public function create(array $payload = []): Model {
        return $this->model->create($payload)->fresh();
    }

    public function update(int $id = 0, array $payload = []): Model {
        $model = $this->findById($id);
        $model->fill($payload);
        $model->save();
        return $model;
    }

    public function findById(int $id = 0, array $relations = []): mixed {
        return $this->model->with($relations)->find($id);
    }

    public function getFillable(): array{
        return $this->model->getFillable();
    }

    public function getRelations(): array {
        return $this->model->getRelations();
    }

    public function delete(Model $model): bool {
        return $model->delete();
    }

    public function bulkDelete(array $ids = []): bool {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function paginate(array $specifications = []){


        return $this->model
        ->keyword($specifications['keyword'] ?? [])
        ->simpleFilter($specifications['filters']['simple'] ?? [])
        ->complexFilter($specifications['filters']['complex'] ?? [])
        ->dateFilter($specifications['filters']['date'] ?? [])
        ->relation($specifications['with'] ?? [])
        ->orderBy($specifications['sort'][0], $specifications['sort'][1])
        ->when($specifications['type'],
            fn($q) => $q->get(),
            fn($q) => $q->paginate($specifications['perpage'])
        );
    }

}