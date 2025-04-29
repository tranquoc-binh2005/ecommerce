<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQuery;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserCatalogue extends Model
{

    use HasQuery;

    protected $fillable = [
        'name',
        'canonical',
        'publish',
        'user_id',
    ];

    public function users(): BelongsToMany{
        return $this->belongsToMany(User::class, 'user_catalogue_user');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_catalogue_permission');
    }

    public function getRelations(): array {
        return ['users', 'permissions'];
    }

}
