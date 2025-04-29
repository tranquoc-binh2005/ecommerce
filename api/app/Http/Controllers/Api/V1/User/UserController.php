<?php 
namespace App\Http\Controllers\Api\V1\User;


use App\Http\Controllers\Controller;
use App\Traits\Loggable;
use App\Http\Requests\User\User\StoreRequest;
use App\Http\Requests\User\User\UpdateRequest;
use App\Http\Requests\User\User\BulkDeleteRequest;
use App\Http\Resources\ApiResource;
use App\Http\Controllers\Api\V1\BaseController;
use App\Services\Interfaces\User\UserServiceInterface as UserService;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController {

    use Loggable;

    protected $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
        parent::__construct(
            $userService,
            UserResource::class
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