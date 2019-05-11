<?php

namespace Dluwang\Auth\Tests\Feature;

use Illuminate\Foundation\Application;
use Kastengel\Packdev\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstallCommandTest extends TestCase
{
    /**
     * Test it can be installed.
     *
     * @return void
     */
    public function testItCanBeInstalled()
    {
        $this->app->setBasePath(realpath(__DIR__.'/../../vendor/laravel/laravel'))
                  ->useAppPath(__DIR__.'/../../vendor/laravel/laravel/app');
        
        $migrationFileIterator = $this->getMigrationFileIterator();

        $migrationFileIterator['permissionMigration']->each(function($item){
            @unlink($item);
        });

        $migrationFileIterator['roleMigration']->each(function($item){
            @unlink($item);
        });

        $migrationFileIterator['permissionRoleMigration']->each(function($item){
            @unlink($item);
        });

        $migrationFileIterator['roleUserMigration']->each(function($item){
            @unlink($item);
        });

        $this->artisan('dluwang-auth:install')
             ->assertExitCode(0);

        $migrationFileIterator = $this->getMigrationFileIterator();

        $this->assertSame(1, $migrationFileIterator['permissionMigration']->count());
        $this->assertSame(1, $migrationFileIterator['roleMigration']->count());
        $this->assertSame(1, $migrationFileIterator['permissionRoleMigration']->count());
        $this->assertSame(1, $migrationFileIterator['roleUserMigration']->count());
        $this->assertTrue(class_exists($this->app->getNamespace() . 'Role'));
        $this->assertTrue(class_exists($this->app->getNamespace() . 'Permission'));
    }

    
    /**
     * Test if it wont copy migration files if exists
     * 
     * @return  void
     */
    public function testItWontCopyStubIfFileExists()
    {
        $this->app->setBasePath(realpath(__DIR__.'/../../vendor/laravel/laravel'))
                  ->useAppPath(__DIR__.'/../../vendor/laravel/laravel/app');

        $this->artisan('dluwang-auth:install');

        $this->artisan('dluwang-auth:install')
             ->expectsOutput('File *_create_roles_table.php exists, skipping.')
             ->expectsOutput('File *_create_permissions_table.php exists, skipping.')
             ->expectsOutput('File *_create_permission_role_table.php exists, skipping.')
             ->expectsOutput('File *_create_role_user_table.php exists, skipping.')
             ->expectsOutput('Class Role exists, skipping.')
             ->expectsOutput('Class Permission exists, skipping.')
             ->assertExitCode(0);
    }

    /**
     * Retrive migration files iterator.
     * 
     * @return  array
     */
    protected function getMigrationFileIterator()
    {
        $permissionMigration = collect(glob($this->app->databasePath('migrations').'/*_create_permissions_table.php'));
        $roleMigration = collect(glob($this->app->databasePath('migrations').'/*_create_roles_table.php'));
        $permissionRoleMigration = collect(glob($this->app->databasePath('migrations').'/*_create_permission_role_table.php'));
        $roleUserMigration = collect(glob($this->app->databasePath('migrations').'/*_create_role_user_table.php'));

        return compact('permissionMigration', 'roleMigration', 'permissionRoleMigration', 'roleUserMigration');
    }
}
