<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQuery;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostCatalogue extends Model
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
        'parent_id',
        'lft',
        'rgt',
        'level',
        'publish',
        'user_id',
    ];
    protected $casts = [
        'album' => 'json'
    ];

    public function getRelations(): array
    {
        return [];
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
