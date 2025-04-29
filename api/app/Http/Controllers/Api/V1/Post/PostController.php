<?php
namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Requests\Post\Post\UpdateRequest;
use App\Traits\Loggable;
use App\Http\Requests\Post\Post\StoreRequest;
use App\Http\Controllers\Api\V1\BaseController;
use App\Services\Interfaces\Post\PostServiceInterface as PostService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Post\PostResource;
use Illuminate\Http\Request;
use App\Http\Requests\Post\Post\BulkDeleteRequest;

class PostController extends BaseController {

    use Loggable;

    protected PostService $postService;

    public function __construct(
        PostService $postService
    ){
        $this->postService = $postService;
        parent::__construct(
            $postService,
            PostResource::class
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
