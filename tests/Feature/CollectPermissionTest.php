<?php

namespace Dluwang\Auth\Tests\Feature;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dluwang\Auth\Services\PolicyTransformer\Contract as PoliciesTransformerContract;

class CollectPermissionTest extends TestCase
{
    /**
     * Test it can collect permissions.
     *
     * @return void
     */
    public function testItCanCollectPermissions(): void
    {
        $this->prepare();

        $this->artisan('dluwang-auth:collect-permissions')
             ->expectsOutput('Registering abilities...')
             ->expectsOutput('Registering permission-test ability...')
             ->expectsOutput('Registering dluwang-auth-tests-user.create ability...')
             ->expectsOutput('Abilities registered.')
             ->assertExitCode(0);

        $this->assertDatabaseHas('permissions', [
            'id' => 'permission-test'
        ]);
    }

    /**
     * Test it won't register permission twice.
     * 
     * @return  void
     */
    public function testItWontRegisterPermissionTwice(): void
    {
        $this->prepare();

        $this->artisan('dluwang-auth:collect-permissions');
        $this->artisan('dluwang-auth:collect-permissions')
             ->expectsOutput('Registering abilities...')
             ->expectsOutput('Permission "permission-test" has been registered, skipping.')
             ->expectsOutput('Permission "dluwang-auth-tests-user.create" has been registered, skipping.')
             ->expectsOutput('Abilities registered.')
             ->assertExitCode(0);
    }

    /**
     * Prepare gate for tests.
     * 
     * @return  void
     */
    protected function prepare(): void
    {
        config(['dluwang-auth.entities.permission' => \Dluwang\Auth\Tests\Permission::class]);
        $gate = $this->app->make(Gate::class);

        $gate->define('permission-test', function($user){
            return true;
        });

        $gate->policy(\Dluwang\Auth\Tests\User::class, UserPolicy::class);
        $this->artisan('dluwang-auth:install');
        $this->artisan('migrate');
    }
}

class UserPolicy
{
    /**
     * No comment. :p
     */
    public function create(User $user)
    {
        return true;
    }
}