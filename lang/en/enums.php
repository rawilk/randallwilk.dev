<?php

return [
    'permission' => [
        'permissions' => ['assign' => 'User can assign permissions directly to other users.'],
        'repositories' => ['manage' => 'User can manage the open source repositories.'],
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
    'repository_sort' => [
        'downloads' => 'by downloads',
        'name' => 'by name',
        'repository_created_at' => 'by date',
        'stars' => 'by popularity',
    ],
    'repository_type' => ['package' => 'Package', 'project' => 'Project'],
    'skills' => [
        'services' => 'Services',
        'skill_stack' => 'Technical Skills',
        'tech' => 'Dev Skills',
    ],
    'theme_select' => ['dark' => 'Dark', 'light' => 'Light', 'system' => 'System'],
];
