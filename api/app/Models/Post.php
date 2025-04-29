<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQuery;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasQuery;
    protected $fillable = [
        'name',
        'canonical',
        'description',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'image',
        'icon',
        'album',
        'post_catalogue_id',
        'publish',
        'user_id',
    ];

    protected $casts = [
        'album' => 'json'
    ];

    public function getRelations(): array
    {
        return ['post_catalogues'];
    }

    protected function post_catalogues(): BelongsToMany
    {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post');
    }
}
