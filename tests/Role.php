<?php

namespace Dluwang\Auth\Tests;

use Illuminate\Database\Eloquent\Model;
use Dluwang\Auth\Concerns\Grantable;

class Role extends Model
{
    use Grantable;

    /**
     * Define permissions relationship.
     * 
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany  
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
