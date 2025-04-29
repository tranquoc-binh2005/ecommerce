<?php
namespace App\Services\Impl\Upload;

use Intervention\Image\ImageManager;
use App\Enums\Config\Common;
use App\Pipelines\Image\ImagePipelineManager;

class ImageService
{
    private $auth;
    private $config;
    protected array $uploadFiles = [];
    protected array $errors = [];

    protected ImagePipelineManager $imageManager;

    public function __construct(
        ImagePipelineManager $imageManager,
    )
    {
        $this->auth = auth(Common::API);
        $this->imageManager = $imageManager;
        $this->config = config('upload.image');
    }

    public function upload($files, $folder = null, $pipelineKey = null, array $overrideOptions = [])
    {
        try {
            if($files){
                $this->uploadFiles = [];
                $this->errors = [];

                if(is_array($files) && count($files)){
                    return $this->multipleUpload($files, $folder, $pipelineKey, $overrideOptions);
                }

                return $this->singleUpload($files, $folder, $pipelineKey, $overrideOptions);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function singleUpload($file, $folder = null, $pipelineKey = null, array $overrideOptions = []): array
    {
        try {
            $result = $this->handleUpload($file, $folder, $pipelineKey, $overrideOptions);
            $this->uploadFiles = $result;
        } catch (\Exception $e){
            $this->errors[] = [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ];
        }

        return $this->generateResponse();
    }

    private function multipleUpload($files, $folder, $pipelineKey, $overrideOptions): array
    {
        $this->uploadFiles = [];
        $this->errors = [];
        foreach ($files as $file) {
            try {
                $result = $this->handleUpload($file, $folder, $pipelineKey, $overrideOptions);
                $this->uploadFiles[] = $result;
            } catch (\Exception $e){
                $this->errors[] = [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ];
                return $this->generateResponse();
            }
        }
        return $this->generateResponse();
    }

    private function handleUpload($file, $folder, $pipelineKey, $overrideOptions): array
    {
        $overrideOptions['storage'] = array_merge(
            $overrideOptions['storage'] ?? [],
            ['path' => $this->buildPath($folder)],
        );

        $image = ImageManager::gd()->read($file);
        $image->originalFile = $file;

        $processImage = $this->imageManager->process($image, $pipelineKey, $overrideOptions);
        return [
            'path' => $processImage->path,
        ];
    }

    private function generateResponse(): array
    {
        $response = [
            'success' => count($this->errors) === 0,
            'files' => $this->uploadFiles,
            'total_uploaded' => count($this->uploadFiles),
        ];

        if(!empty($this->errors)){
            $response['error'] = $this->errors;
        }
        return $response;
    }

    private function buildPath(string $folder = ''): string
    {
        return trim($this->config['base_path'] . '/' . $folder . '/');
    }
}
