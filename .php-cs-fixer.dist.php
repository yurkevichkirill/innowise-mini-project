<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PHPyh\CodingStandard\PhpCsFixerCodingStandard;

$config = new Config()
    ->setFinder(
        (new Finder())
            ->in(__DIR__)
            ->name('*.php')           // ← Только PHP
            ->exclude('vendor')
            ->exclude('var')
            ->exclude('storage')
            ->exclude('bootstrap/cache')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    );

new PhpCsFixerCodingStandard()->applyTo($config);

return $config;