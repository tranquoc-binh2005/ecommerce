<?php
namespace App\Pipelines\Image\Pipes;

use App\Pipelines\Image\Pipes\AbstractImagePipeline;
use Illuminate\Support\Str;

class GenerateFileNamePipeline extends AbstractImagePipeline
{

    public function handle($image, \closure $next)
    {
        if(!isset($image->fileName)){
            $originalName = $image->originalFile->getClientOriginalName();
            $extension = $image->originalFile->getClientOriginalExtension();

            $image->fileName = Str::uuid() . '.' . $extension;
            $image->originalName = $originalName;
        }
        return $next($image);
    }

}
