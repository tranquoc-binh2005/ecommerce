<?php
namespace App\Pipelines\Image;

use App\Pipelines\Image\Pipes\GenerateFileNamePipeline;
use App\Pipelines\Image\Pipes\StorageImagePipeline;
use Illuminate\Pipeline\Pipeline;
use App\Pipelines\Image\Pipes\OptimizeImagePipeline;
class ImagePipelineManager
{
    protected array $defaultPipeline = [
        'generate_filename' => GenerateFileNamePipeline::class,
        'optimize' => OptimizeImagePipeline::class,
        'storage' => StorageImagePipeline::class,
    ];

    public function process($image, string $pipelineKey = '', array $overrideOptions = [])
    {
        $pipelineConfig = config("upload.image.pipelines.{$pipelineKey}");
        $pipes = collect($pipelineConfig)->filter(fn($config) => $config['enabled'] ?? true)
                ->map(function ($config, $pipeName) use ($overrideOptions) {
                    $class = $this->defaultPipeline[$pipeName] ?? null;
                    if(!$class){
                        return null;
                    }
                    return new $class(array_merge(
                        $config,
                        $overrideOptions[$pipeName] ?? [],
                        []
                    ));
                })->filter()->values()->toArray();
        return app(Pipeline::class)->send($image)->through($pipes)->thenReturn();
    }
}
