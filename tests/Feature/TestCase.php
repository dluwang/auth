<?php

namespace Dluwang\Auth\Tests\Feature;

use Kastengel\Packdev\Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->app->setBasePath(realpath(__DIR__.'/../../vendor/laravel/laravel'))
                  ->useAppPath(__DIR__.'/../../vendor/laravel/laravel/app');
        
        $this->artisan('dluwang-auth:install');
        $this->artisan('migrate');
    }
}