<?php

declare(strict_types=1);

return [
    'sections' => [
        'info' => [
            'heading' => 'Repository information',
        ],

        'github_meta' => [
            'heading' => 'GitHub meta',
        ],

        'meta' => [
            'heading' => 'Repository meta',
        ],
    ],

    'attributes' => [
        'id' => [
            'label' => 'ID',
        ],

        'h_key' => [
            'label' => 'Human key',
        ],

        'name' => [
            'label' => 'Repository name',
        ],

        'scoped_name' => [
            'label' => 'Scoped name',
            'placeholder' => 'Not scoped',
            'help' => 'Mostly for NPM packages; this allows the system to query for downloads based on a scoped namespace.',
        ],

        'description' => [
            'label' => 'Description',
        ],

        'type' => [
            'label' => 'Repository type',
            'placeholder' => 'Not set',
        ],

        'language' => [
            'label' => 'Primary language',
        ],

        'visible' => [
            'true' => 'Visible',
            'false' => 'Hidden',
        ],

        'documentation_url' => [
            'label' => 'Documentation URL',
        ],

        'blogpost_url' => [
            'label' => 'Blog post URL',
        ],

        'new' => [
            'label' => 'Marked as new',
        ],

        'highlighted' => [
            'label' => 'Marked as featured',
        ],

        'stars' => [
            'label' => 'Stars',
        ],

        'downloads' => [
            'label' => 'Downloads',
        ],

        'topics' => [
            'label' => 'Topics',
        ],

        'repository_created_at' => [
            'label' => 'Creation date',
            'help' => 'Date repository was created on GitHub.',
        ],
    ],

    'actions' => [
        'sync' => [
            'label' => 'Sync repository info',
            'success' => 'Repository info queued to sync!',
        ],

        'import_docs' => [
            'label' => 'Import docs',
            'success' => 'Repository docs queued to import!',
        ],
    ],
];
