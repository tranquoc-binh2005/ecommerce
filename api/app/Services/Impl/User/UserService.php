<?php
namespace App\Services\Impl\User;
use App\Enums\Config\Common;
use App\Services\Interfaces\User\UserServiceInterface;
use App\Services\Impl\BaseService;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use App\Services\Impl\Upload\ImageService;
use Illuminate\Support\Str;

class UserService extends BaseService implements UserServiceInterface {

    private $repository;
    private $auth;
    protected ImageService $imageService;

    private const USERS = 'users';
    private const PIPE_LINE_KEY = 'default';

    public function __construct(
        UserRepository $repository,
        ImageService  $imageService,
    ){
        $this->repository = $repository;
        $this->cacheKeyPrefix = self::USERS;
        $this->imageService = $imageService;
        $this->auth = auth(Common::API);
        parent::__construct($repository);
    }


    /**
     * @throws \Exception
     */
    protected function prepareModelData(Request $request) :self{

        return $this->initializeBasicData($request)->uploadAvatarImage($request);
    }

    protected function initializeBasicData(Request $request): self {
        $fillable = $this->repository->getFillable();
        $this->modelData = $request->only($fillable);
        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function uploadAvatarImage(Request $request) :self{
        try {

            $uploadConfig = [
                'files' => $request->file('image'),
                'folder' => Str::before($this->auth->user()->email, '@').'/avatar/'.now()->format('Ymd'),
                'pipelineKey' => self::PIPE_LINE_KEY,
                'overrideOptions' => [
                    'optimize' => [
                        'quality' => 45,
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
}
