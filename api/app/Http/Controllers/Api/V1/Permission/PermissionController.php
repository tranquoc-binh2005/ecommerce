<?php
namespace App\Http\Controllers\Api\V1\Permission;

use App\Http\Requests\Permission\Permission\UpdateRequest;
use App\Traits\Loggable;
use App\Http\Requests\Permission\Permission\StoreRequest;
use App\Http\Controllers\Api\V1\BaseController;
use App\Services\Interfaces\Permission\PermissionServiceInterface as PermissionService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Permission\PermissionResource;
use Illuminate\Http\Request;
use App\Http\Requests\Permission\Permission\BulkDeleteRequest;

class PermissionController extends BaseController {

    use Loggable;

    protected PermissionService $permissionService;

    public function __construct(
        PermissionService $permissionService
    ){
        $this->permissionService = $permissionService;
        parent::__construct(
            $permissionService,
            PermissionResource::class
        );
    }

    public function index(Request $request): JsonResponse
    {
        return $this->baseIndex($request);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        return $this->baseSave($request);
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        return $this->baseSave($request, $id);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        return $this->baseShow($id);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->baseDestroy($id);
    }

    public function detach(Request $request): JsonResponse
    {
        return $this->attachOrDetach($request, 'detach');
    }

    public function attach(Request $request): JsonResponse
    {
        return $this->attachOrDetach($request, 'attach');
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        return $this->baseBulkDelete($request);
    }
}
