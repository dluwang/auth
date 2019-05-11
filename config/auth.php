<?php

return [
    'entities' => [
        'grantable' => App\Role::class,
        'permission' => App\Permission::class,
    ],
    'policy' => [
        'transformer' => Dluwang\Auth\Services\PolicyTransformer\SimplePolicyTransformer::class
    ]
];