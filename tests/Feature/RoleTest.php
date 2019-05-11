<?php

namespace Dluwang\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if it exists.
     *
     * @return  void
     */
    public function testItExists()
    {
        $role = factory(\Dluwang\Auth\Tests\Role::class)->create();

        $this->assertDatabaseHas('roles', $role->toArray());
    }

    /**
     * Test if it grantable.
     * 
     * @return  void
     */
    public function testItIsGrantable()
    {
        $role = factory(\Dluwang\Auth\Tests\Role::class)->create();
        $permissions = factory(\Dluwang\Auth\Tests\Permission::class, 3)->create();
        $permissionIds = $permissions->reduce(function($permissions, $permission){
            $permissions[] = $permission->id;

            return $permissions;
        }, []);

        $role->grant($permissionIds);
        $this->assertSame($role->permissions->count(), 3);
        
        $role->revoke($permissions->first()->id);
        $role->load('permissions');
        $this->assertSame($role->permissions->count(), 2);
    }
}
