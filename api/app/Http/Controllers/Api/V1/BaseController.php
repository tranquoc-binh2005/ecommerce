<?php 
namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Enums\Config\Common;
use Illuminate\Support\Facades\Lang;
use App\Exceptions\RecordNotMatchException;

class BaseController extends Controller {

    use Loggable;

    private $service;
    private $resource;

    public function __construct(
        $service, $resource
    )
    {
       $this->service = $service;
       $this->resource = $resource;
    }
    
    public function baseIndex(Request $request): JsonResponse {
        try {
            $response = $this->service->paginate($request);
            if($response instanceof \Illuminate\Pagination\LengthAwarePaginator){
                $resource = $response->through(function($item){
                    return new $this->resource($item);
                });
            }else if($response instanceof \Illuminate\Database\Eloquent\Collection){
                $resource = $response->map(function($item){
                    return new $this->resource($item);
                });
            }
            return ApiResource::ok($resource, Common::SUCCESS);
        }  catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }

    public function baseSave(Request $request, ?int $id = null): JsonResponse{
        try {
            $response = $this->service->save($request, $id);
            $resource = new $this->resource($response);
            return ApiResource::ok($resource, Common::SUCCESS);
        } catch (ModelNotFoundException $e){
            return ApiResource::message($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }

    
    public function baseShow(int $id): JsonResponse{
        try {
            
            $response = $this->service->show($id);
            $resource = new $this->resource($response);
            return ApiResource::ok($resource, Common::SUCCESS);

        } catch (ModelNotFoundException $e){
            return ApiResource::message($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }

    public function baseDestroy(int $id): JsonResponse {
        try {
            $response = $this->service->destroy($id);
            return ApiResource::message(Lang::get('message.delete_success'));
        } catch (ModelNotFoundException $e){
            return ApiResource::message($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }

    public function baseBulkDelete(Request $request): JsonResponse {
        try {
            $response = $this->service->bulkDelete($request);
            return ApiResource::message(Lang::get('message.delete_success'). '('.count($request->ids).')');
        } catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }

    public function attachOrDetach(Request $request, string $action = ''): JsonResponse {
        try {
            $response = $this->service->attachOrDetach($request, $action);
            return ApiResource::message(Lang::get("message.{$action}_success") . '('.count($request->ids).')');
        } catch (ModelNotFoundException $e){
            return ApiResource::message($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch(RecordNotMatchException $e){
            return ApiResource::message($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->handleLogException($e);
        }
    }
    
}




