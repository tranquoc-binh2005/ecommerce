<?php  
namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Lang;
use App\Exceptions\RecordNotMatchException;

trait HasRelation {
    
    protected function handleRelations(Request $request): self {
        $relations = $this->baseRepository->getRelations();
        if(count($relations)){
            foreach($relations as $key => $relation){
                if($request->has($relation)){
                    $this->model->{$relation}()->sync($request->{$relation});
                }
            }
        }
        return $this;
    }

    protected function actachOrDetachModelRelation(Request $request, string $action = ''): self {
        try {
            if(!$model = $this->baseRepository->findById($request->id)){
                throw new ModelNotFoundException(Lang::get('message.not_found'));
            }

            $relationModels = DB::table($request->relation)->whereIn('id', $request->ids)->get();
            if(count($relationModels) !== count($request->ids)){
                throw new RecordNotMatchException(Lang::get('message.record_not_match'));
            }

            $this->model = $model;
            $this->result = $model->{$request->relation}()->{$action}($request->ids);

           return $this;
        } catch (\Exception $e) {
            throw $e;
        } 
    }

}