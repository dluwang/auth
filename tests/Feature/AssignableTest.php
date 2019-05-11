<?php

namespace Dluwang\Auth\Tests\Feature;

use Dluwang\Auth\Concerns\Assignable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if it can be assigned
     * 
     * @return  void
     */
    public function testItIsAssignable()
    {
        config(['dluwang-auth.entities.grantable' => \Dluwang\Auth\Tests\Role::class]);
        
        $user = factory(\Dluwang\Auth\Tests\User::class)->create();
        $roles = factory(config('dluwang-auth.entities.grantable'), 3)->create();
        $roleIds = $roles->reduce(function($roles, $role){
            $roles[] = $role->id;

            return $roles;
        }, []);

        $user->assign($roleIds);
        $this->assertSame($user->roles->count(), 3);

        $user->reject($roles->first()->id);
        $user->load('roles');
        $this->assertSame($user->roles->count(), 2);
    }

    /**
     * Test if it has permissions.
     * 
     * @return  void
     */
    public function testItHasPermissions()
    {
        config(['dluwang-auth.entities.grantable' => \Dluwang\Auth\Tests\Role::class]);
        config(['dluwang-auth.entities.permission' => \Dluwang\Auth\Tests\Permission::class]);
        
        $permissions = factory(config('dluwang-auth.entities.permission'), 3)->create();
        $roles = factory(config('dluwang-auth.entities.grantable'), 3)->create();
        $user = factory(\Dluwang\Auth\Tests\User::class)->create();

        $roles->first()->grant($permissions);

        $roleIds = $roles->reduce(function($roles, $role){
            $roles[] = $role->id;

            return $roles;
        }, []);

        $user->assign($roleIds);
        $this->assertSame($user->permissions->count(), 3);
        $this->assertTrue($user->authorized($permissions->first()->id));
        $this->assertTrue($user->authorizedOneOf([$permissions->first()->id, 'dummy-permission']));
    }
}
