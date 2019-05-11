<?php

namespace Dluwang\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if it exists.
     *
     * @return  void
     */
    public function testItExists()
    {
        $permission = factory(\Dluwang\Auth\Tests\Permission::class)->create();

        $this->assertDatabaseHas('permissions', $permission->toArray());
    }
}
