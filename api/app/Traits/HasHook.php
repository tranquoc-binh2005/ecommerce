<?php
namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Lang;

trait HasHook {
    protected function beginTransaction(): self{
        DB::beginTransaction();
        return $this;
    }

    protected function commit(): self{
        DB::commit();
        return $this;
    }

    protected function rollback(): self {
        DB::rollBack();
        return $this;
    }

    protected function saveModel(?int $id = null): self{
        if($id){
            $this->model = $this->baseRepository->update($id, $this->modelData);
        }else{
            $this->model = $this->baseRepository->create($this->modelData);
        }
        $this->result = $this->model;
        return $this;
    }

    protected function beforeSave(): self {
        return $this;
    }

    protected function afterSave():self {
        return $this->clearSingleRecordCache()->cacheSingleRecord()->clearCollectionRecordCache();
    }

    protected function getResult(): mixed {
        return $this->result;
    }

    protected function afterDelete(): self{
        return $this->clearSingleRecordCache()->clearCollectionRecordCache();
    }

    protected function afterBulkDelete(array $ids = []): self {

        return $this->clearCollectionRecordCache()->clearBulkDeleteCache($ids);

    }

    protected function beforeDelete(int $id): self {
        if(! $this->model = $this->baseRepository->findById($id)){
             throw new ModelNotFoundException(Lang::get('message.not_found'));
        }
        return $this;
    }

    protected function deleteModel(): self {
        $this->result = $this->baseRepository->delete($this->model);
        return $this;
    }

    protected function bulkDeleteModel(array $ids = []): self {
        $this->result = $this->baseRepository->bulkDelete($ids);
        return $this;
    }

    protected function callNested($nested): void
    {
        $nested->get();
        $nested->recursive(0, $nested->set());
        $nested->action();
    }

}
