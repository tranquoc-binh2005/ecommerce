<?php
namespace App\Repositories\Post;

use App\Models\PostCatalogue;
use App\Repositories\BaseRepository;

class PostCatalogueRepositories extends BaseRepository
{
    protected $model;
    public function __construct(
        PostCatalogue $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}
