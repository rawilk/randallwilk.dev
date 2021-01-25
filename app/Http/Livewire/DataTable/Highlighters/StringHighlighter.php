<?php

declare(strict_types=1);

namespace App\Http\Livewire\DataTable\Highlighters;

final class StringHighlighter implements Highlighter
{
    public function highlight($value, $search)
    {
        preg_match_all('#' . preg_quote($search) . '#i', $value, $matches);

        $matches = collect($matches[0] ?? [])->unique();

        foreach ($matches as $match) {
            $value = str_replace($match, view('components.table.highlight', ['slot' => $match])->render(), $value);
        }

        return str_replace(PHP_EOL, '', $value);
    }
}
