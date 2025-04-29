<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQuery;

class Permission extends Model
{
    use HasQuery;
    protected $fillable = [
        'name',
        'title',
        'value',
        'module',
        'description',
        'publish',
        'user_id',
    ];

    public function getRelations(): array
    {
        return [];
    }
}
