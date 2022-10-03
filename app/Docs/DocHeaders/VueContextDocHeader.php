<?php

declare(strict_types=1);

namespace App\Docs\DocHeaders;

final class VueContextDocHeader implements DocHeader
{
    public static function heroTabs(string $version): array
    {
        return [
            'users.vue',
            'package.json',
        ];
    }

    public static function snippetLanguage(string $version): string
    {
        return 'html';
    }

    public static function snippet(string $version): string
    {
        return <<<'HTML'
        <div
          @contextmenu.prevent="$refs.menu.open($event, { id: 1 })"
         >
            John Smith
        </div>

        <vue-context ref="menu" v-slot="{ data }">
            <li>
                <a @click.prevent="edit(data.id)">
                    Edit
                </a>
            </li>
        </vue-context>
        HTML;
    }
}
