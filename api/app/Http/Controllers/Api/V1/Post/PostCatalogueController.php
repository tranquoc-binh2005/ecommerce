<?php
namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Requests\Post\PostCatalogue\UpdateRequest;
use App\Traits\Loggable;
use App\Http\Requests\Post\PostCatalogue\StoreRequest;
use App\Http\Controllers\Api\V1\BaseController;
use App\Services\Interfaces\Post\PostCatalogueServiceInterface as PostCatalogueService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Post\PostCatalogueResource;
use Illuminate\Http\Request;
use App\Http\Requests\Post\PostCatalogue\BulkDeleteRequest;

class PostCatalogueController extends BaseController {

    use Loggable;

    protected PostCatalogueService $postCatalogueService;

    public function __construct(
        PostCatalogueService $postCatalogueService
    ){
        $this->postCatalogueService = $postCatalogueService;
        parent::__construct(
            $postCatalogueService,
            PostCatalogueResource::class
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
