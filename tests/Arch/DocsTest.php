<?php

declare(strict_types=1);

use App\Docs\DocHeaders\DocHeader;
use App\Docs\DocHeaders\DocHeaderFactory;

arch()->expect('App\Docs\DocHeaders')
    ->classes()
    ->toImplement(DocHeader::class)
    ->ignoring([
        DocHeaderFactory::class,
    ]);

arch()->expect('App\Docs\DocHeaders')
    ->classes()
    ->toHaveSuffix('DocHeader')
    ->ignoring([
        DocHeaderFactory::class,
    ]);
