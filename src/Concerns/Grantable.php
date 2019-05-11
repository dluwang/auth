<?php

namespace Dluwang\Auth\Concerns;

trait Grantable
{
    /**
     * Grant permissions to role.
     * 
     * @param   array|string|int
     * 
     * @return  void
     */
    public function grant($permissions): void
    {
        $this->permissions()->attach($permissions);
    }

    /**
     * Revoke permissions from role.
     * 
     * @param   array|string|int    $permissions
     * 
     * @return  void
     */
    public function revoke($permissions): void
    {
        $this->permissions()->detach($permissions);
    }
}