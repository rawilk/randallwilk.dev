<?php

return [
    'default_collection' => null,

    'collections' => [

        'docs' => [
            'disk' => 'docs',
            'sheet_class' => \App\Docs\DocumentationPage::class,
            'path_parser' => \App\Docs\DocumentationPathParser::class,
            'content_parser' => \App\Docs\DocumentationContentParser::class,
            // 'content_parser' => \App\Support\Sheets\ContentParser::class,
        ],

    ],
];
