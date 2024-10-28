<?php

declare(strict_types=1);

return [
    'navigation_label' => 'Repositories',

    'model' => [
        'singular' => 'repository',
        'plural' => 'repositories',
    ],

    'actions' => [
        'edit_bulk' => [
            'label' => 'Edit selected',
            'modal_heading' => 'Edit selected repositories',
        ],

        'sync' => [
            'label' => 'Sync repositories',
            'success' => 'Repositories were queued to sync!',
        ],

        'import_docs' => [
            'label' => 'Import docs',
            'success' => 'Package docs have been queued to import!',
        ],

        'delete' => [
            'modal_description' => 'This will only soft-delete the repository "**:name**"; it will still remain in the database.',
        ],
    ],

    'form' => [
        'type' => [
            'label' => 'Repository type',
        ],

        'scoped_name' => [
            'label' => 'Scoped name',
            'placeholder' => '@wilkr/example-package',
            'help' => 'Mostly for NPM packages; this allows the system to query for downloads based on a scoped namespace.',
        ],

        'documentation_url' => [
            'label' => 'Documentation url',
            'placeholder' => 'https://randallwilk.dev/docs/example-package',
        ],

        'blogpost_url' => [
            'label' => 'Blog post url',
            'placeholder' => 'https://example.com/example-package',
        ],

        'visible' => [
            'label' => 'Visible on frontend',
            'help' => 'Un-checking this will prevent this repository from showing up on the front-end of the site.',
        ],

        'highlighted' => [
            'label' => 'Featured',
            'help' => 'Check to show the repository as "featured" on the front-end.',
        ],

        'new' => [
            'label' => 'New repository',
            'help' => 'Check to display a "new" badge by the repository on the front-end.',
        ],
    ],

    'table' => [
        'name' => [
            'label' => 'Repository',
        ],

        'type' => [
            'label' => 'Type',
        ],

        'downloads' => [
            'label' => 'Downloads',
        ],

        'stars' => [
            'label' => 'Stars',
        ],

        'visible' => [
            'label' => 'Visible',
        ],

        'new' => [
            'label' => 'Is new',
        ],

        'highlighted' => [
            'label' => 'Featured',
        ],

        'documentation_url' => [
            'label' => 'Has docs',
        ],

        'blogpost_url' => [
            'label' => 'Has blog post',
        ],

        'filters' => [
            'has_type' => [
                'label' => 'Has type',
            ],
        ],
    ],

    'tabs' => [
        'not_trashed' => [
            'label' => 'Active',
        ],

        'trashed' => [
            'label' => 'Deleted',
        ],
    ],

    'widgets' => [
        'visible_repos' => [
            'label' => 'Visible Repositories',
        ],
    ],
];
