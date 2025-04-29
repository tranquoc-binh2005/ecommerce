<?php
namespace App\Pipelines\Image\Pipes;

abstract class AbstractImagePipeline
{
    protected array $options;
    public function  __construct(
        array $options = []
    )
    {
        $this->options = $options;
    }
}
