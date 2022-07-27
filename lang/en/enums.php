<?php

return [
    'permission' => [
        'permissions' => ['assign' => 'User can assign permissions directly to other users.'],
        'roles' => [
            'assign' => 'User can assign roles to users.',
            'create' => 'User can create new roles.',
            'delete' => 'User can delete roles.',
            'edit' => 'User can edit existing roles.',
        ],
        'users' => [
            'create' => 'User can create other user accounts.',
            'delete' => 'User can delete other user accounts.',
            'edit' => 'User can edit other user accounts.',
            'impersonate' => 'User can impersonate other user accounts.',
        ],
    ],
];
