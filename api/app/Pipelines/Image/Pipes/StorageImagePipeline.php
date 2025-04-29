<?php
namespace App\Pipelines\Image\Pipes;

use App\Pipelines\Image\Pipes\AbstractImagePipeline;
use Illuminate\Support\Facades\Storage;
class StorageImagePipeline extends AbstractImagePipeline
{
    public function handle($image, \closure $next)
    {
        $disk = $this->options['disk'] ?? config('upload.image.disk');
        $path = trim($this->options['path'] . $image->fileName, '/');

        Storage::disk($disk)->put($path, (string)$image->encode($image->encoder));
        $image->path = $path;

        return $next($image);
    }
}
