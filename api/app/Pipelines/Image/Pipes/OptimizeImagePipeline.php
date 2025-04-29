<?php
namespace App\Pipelines\Image\Pipes;

use App\Pipelines\Image\Pipes\AbstractImagePipeline;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\GifEncoder;

class OptimizeImagePipeline extends AbstractImagePipeline
{

    public function handle($image, \closure $next)
    {
        $quality = $this->options['quality'];
        $mime = $image->origin()->mediaType();

        $encoder = match ($mime) {
            'image/webp' => new WebpEncoder($quality),
            'image/jpeg' => new JpegEncoder($quality),
            'image/png' => new PngEncoder($quality),
            'image/gif' => new GifEncoder($quality),
        };
        $image->encoder = $encoder;
        return $next($image);
    }

}
