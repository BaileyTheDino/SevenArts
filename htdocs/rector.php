<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withPhpSets(
        php84: true,
    )
    ->withSkip([
        SimplifyBoolIdenticalTrueRector::class,     /* Skipped for readability. */
        SimplifyIfReturnBoolRector::class,          /* Skipped for readability. */
        SimplifyIfElseToTernaryRector::class,       /* Skipped for readability. */
    ]);
