<?php 
namespace App\Http\Controllers\Api\V1\User;


use App\Http\Controllers\Controller;
use App\Traits\Loggable;
use App\Http\Requests\User\Catalogue\StoreRequest;
use App\Http\Requests\User\Catalogue\UpdateRequest;
use App\Http\Requests\User\Catalogue\BulkDeleteRequest;
use App\Http\Resources\ApiResource;
use App\Http\Controllers\Api\V1\BaseController;
use App\Services\Interfaces\User\UserCatalogueServiceInterface as UserCatalogueService;
use App\Http\Resources\User\UserCatalogueResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCatalogueController extends BaseController {

    use Loggable;

    protected $userCatalogueService;

    public function __construct(
        UserCatalogueService $userCatalogueService
    )
    {
        $this->userCatalogueService = $userCatalogueService;
        parent::__construct(
            $userCatalogueService,
            UserCatalogueResource::class
        );
    }

    public function index(Request $request): JsonResponse{
        return $this->baseIndex($request);
    }

    public function store(StoreRequest $request): JsonResponse{
        return $this->baseSave($request);
    }

    public function update(UpdateRequest $request, int $id): JsonResponse{
        return $this->baseSave($request, $id);
    }

    public function show(Request $request, int $id): JsonResponse{
        return $this->baseShow($id);
    }

    public function destroy(int $id): JsonResponse{
        return $this->baseDestroy($id);
    }

    public function detach(Request $request): JsonResponse{
       return $this->attachOrDetach($request, 'detach');
    }

    public function attach(Request $request): JsonResponse{
        return $this->attachOrDetach($request, 'attach');
    }

    public function bulkDelete(BulkDeleteRequest $request){
        return $this->baseBulkDelete($request);
    }

}