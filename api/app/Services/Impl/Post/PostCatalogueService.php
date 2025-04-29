<?php
namespace App\Services\Impl\Post;

use App\Classes\Nested;
use App\Traits\Loggable;
use App\Services\Interfaces\Post\PostCatalogueServiceInterface;
use App\Services\Impl\BaseService;
use App\Repositories\Post\PostCatalogueRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Impl\Upload\ImageService;
use App\Enums\Config\Common;

class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface{
    use Loggable;

    private $repositories;
    private $auth;
    private ImageService $imageService;
    protected Nested $nested;
    private const CACHE_KEY_PREFIX = 'post_catalogues';
    private const PIPE_LINE_KEY = 'default';
    private const NESTED_SET_TABLE = 'post_catalogues';

    public function __construct(
        PostCatalogueRepositories $repositories,
        ImageService $imageService,
    ){
        $this->repositories = $repositories;
        $this->imageService = $imageService;
        $this->auth = auth(Common::API);
        $this->cacheKeyPrefix = self::CACHE_KEY_PREFIX;
        parent::__construct($repositories);
    }

    /**
     * @throws \Exception
     */
    protected function prepareModelData(Request $request): self
    {
        return $this->initializeBasicData($request)
            ->uploadAvatarImage($request)
            ->uploadIcon($request)
            ->uploadAlbum($request);
    }

    protected function initializeBasicData(Request $request): self{
        $fillAble = $this->repositories->getFillAble();
        $this->modelData = $request->only($fillAble);
        return $this;
    }
    protected function uploadAvatarImage(Request $request) :self{
        try {
            $uploadConfig = [
                'files' => $request->file('image'),
                'folder' => Str::before($this->auth->user()->email, '@').'/post_catalogues/'.now()->format('Ymd'),
                'pipelineKey' => self::PIPE_LINE_KEY,
                'overrideOptions' => [
                    'optimize' => [
                        'quality' => 100,
                    ],
                ]
            ];

            $processImage = $this->imageService->upload(...$uploadConfig);
            $this->modelData['image'] = $processImage['files']['path'] ?? '';
            return $this;
        } catch (\Exception $e){
            throw $e;
        }
    }

    protected function uploadIcon(Request $request) :self{
        try {
            $uploadConfig = [
                'files' => $request->file('icon'),
                'folder' => Str::before($this->auth->user()->email, '@').'/post_catalogues/'.now()->format('Ymd'),
                'pipelineKey' => self::PIPE_LINE_KEY,
                'overrideOptions' => [
                    'optimize' => [
                        'quality' => 100,
                    ],
                ]
            ];

            $processImage = $this->imageService->upload(...$uploadConfig);
            $this->modelData['icon'] = $processImage['files']['path'] ?? '';
            return $this;
        } catch (\Exception $e){
            throw $e;
        }
    }

    protected function uploadAlbum(Request $request) :self{
        try {
            $uploadConfig = [
                'files' => $request->file('album'),
                'folder' => Str::before($this->auth->user()->email, '@').'/post_catalogues/albums/'.now()->format('Ymd'),
                'pipelineKey' => self::PIPE_LINE_KEY,
                'overrideOptions' => [
                    'optimize' => [
                        'quality' => 100,
                    ],
                ]
            ];

            $processImage = $this->imageService->upload(...$uploadConfig);
            $this->modelData['album'] = $processImage['files'] ?? '';
            return $this;
        } catch (\Exception $e){
            throw $e;
        }
    }

    protected function nestedSet(): void
    {
        $this->nested = new Nested([
            'table' => self::NESTED_SET_TABLE
        ]);
        $this->callNested($this->nested);
    }

    protected function afterSave():self {
        $this->nestedSet();
        return $this->clearSingleRecordCache()->cacheSingleRecord()->clearCollectionRecordCache();
    }
}
