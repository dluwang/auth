<?php

namespace Dluwang\Auth\Tests;

use App\User as BaseUser;
use Dluwang\Auth\Concerns\Assignable;

class User extends BaseUser
{
    use Assignable;
}