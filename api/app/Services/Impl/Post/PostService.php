<?php
namespace App\Services\Impl\Post;

use App\Services\Interfaces\Post\PostServiceInterface;
use App\Services\Impl\BaseService;
use App\Repositories\Post\PostRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Impl\Upload\ImageService;
use App\Enums\Config\Common;
class PostService extends BaseService implements PostServiceInterface{

    private $repositories;
    private $auth;
    private ImageService $imageService;
    private const CACHE_KEY_PREFIX = 'posts';
    private const PIPE_LINE_KEY = 'default';

    public function __construct(
        PostRepositories $repositories,
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
            ->uploadImage($request)
            ->uploadIcon($request)
            ->uploadAlbum($request);
    }

    protected function initializeBasicData(Request $request): self{
        $fillAble = $this->repositories->getFillAble();
        $this->modelData = $request->only($fillAble);
        return $this;
    }

    protected function uploadImage(Request $request): self{
        try {
            $uploadConfig = [
                'files' => $request->file('image'),
                'folder' => Str::before($this->auth->user()->email, '@').'/posts/'.now()->format('Ymd'),
                'pipelineKey' => self::PIPE_LINE_KEY,
                'overrideOptions' => [
                    'optimize' => [
                        'quality' => 100
                    ]
                ]
            ];

            $processImage = $this->imageService->upload(...$uploadConfig);
            $this->modelData['image'] =  $processImage['files']['path'] ?? '';
            return $this;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function uploadAvatarImage(Request $request) :self{
        try {
            $uploadConfig = [
                'files' => $request->file('image'),
                'folder' => Str::before($this->auth->user()->email, '@').'/posts/'.now()->format('Ymd'),
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
                'folder' => Str::before($this->auth->user()->email, '@').'/posts/'.now()->format('Ymd'),
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

    protected function uploadAlbum(Request $request): self{
        try {
            $uploadConfig = [
                'files' => $request->file('album'),
                'folder' => Str::before($this->auth->user()->email, '@').'/posts/album/'.now()->format('Ymd'),
                'pipelineKey' => self::PIPE_LINE_KEY,
                'overrideOptions' => [
                    'optimize' => [
                        'quality' => 100
                    ]
                ]
            ];

            $processImage = $this->imageService->upload(...$uploadConfig);
            $this->modelData['album'] =  $processImage['files'] ?? '';
            return $this;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
