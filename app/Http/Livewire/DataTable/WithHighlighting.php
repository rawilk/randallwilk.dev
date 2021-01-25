<?php

namespace App\Http\Livewire\DataTable;

use App\Http\Livewire\DataTable\Highlighters\StringHighlighter;
use App\Http\Livewire\DataTable\Highlighters\ViewHighlighter;

trait WithHighlighting
{
    protected static array $highlighters = [
        'string' => StringHighlighter::class,
        'view' => ViewHighlighter::class,
    ];

    public function highlight($content, string $highlighter = 'string'): string
    {
        if (! $content || ! ($this->filters['search'] ?? '')) {
            return (string) $content;
        }

        $highlighter = static::$highlighters[$highlighter] ?? StringHighlighter::class;

        return (new $highlighter)->highlight($content, $this->filters['search'] ?? '');
    }
}
