<?php

declare(strict_types=1);

namespace App\Support\Database\Redaction\Contracts;

use Illuminate\Console\OutputStyle;
use Illuminate\Console\View\Components\Factory;

interface Redactor
{
    public function handle(): void;

    public function setOutput(OutputStyle $output);

    public function setOutputComponents(Factory $outputComponents);
}
