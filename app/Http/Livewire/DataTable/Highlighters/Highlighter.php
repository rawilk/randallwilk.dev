<?php

namespace App\Http\Livewire\DataTable\Highlighters;

interface Highlighter
{
    public function highlight($value, $search);
}
