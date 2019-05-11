<?php

namespace Dluwang\Auth\Concerns;

trait Assignable 
{
    /**
     * Assign roles to assignable.
     * 
     * @var array|string|int $roles
     * 
     * @return  void
     */
    public function assign($roles): void
    {
        $this->roles()->attach($roles);
    }

    /**
     * Reject roles from assignable.
     * 
     * @var array|string|int $roles
     * 
     * @return  void
     */
    public function reject($roles): void
    {
        $this->roles()->detach($roles);
    }

    /**
     * Define roles relationship.
     * 
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany  
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $grantable = config('dluwang-auth.entities.grantable');

        return $this->belongsToMany($grantable);
    }

    /**
     * Retrieve permissions.
     *
     * @return \Illuminate\Support\Collection;
     */
    public function getPermissionsAttribute(): \Illuminate\Support\Collection
    {
        return $this->roles->map(function($item, $key){
            return $item->permissions;
        })->flatten(1)->unique('id');
    }

    /**
     * Determine if is is authorized.
     * 
     * @param   string|array    $permissions
     * 
     * @return  bool
     */
    public function authorized($permissions): bool
    {
        return $this->countDiffPermissions(is_array($permissions) ? $permissions : [$permissions] ) == 0;
    }

    /**
     * Determine if it is authorized one of permissions.
     * 
     * @param   array   $permissions
     * 
     * @return  bool
     */
    public function authorizedOneOf(array $permissions): bool
    {
        $initial = count($permissions);

        return $this->countDiffPermissions($permissions) < $initial;
    }

    /**
     * Count diff of given permissions with entity's permissions.
     *
     * @param  array $permissions
     *
     * @return integer
     */
    protected function countDiffPermissions(array $permissions): int
    {
        $permissions = collect($permissions);
        $assignablePermissions = $this->permissions->map(function($permission){
            return $permission->id;
        });

        return $permissions->diff($assignablePermissions)->count();
    }
}