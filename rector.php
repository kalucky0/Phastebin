<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector;
use Rector\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->sets([
        SetList::CODE_QUALITY
    ]);

    $rectorConfig->rule(ArrayMergeOfNonArraysToSimpleArrayRector::class);
    $rectorConfig->rule(CompleteDynamicPropertiesRector::class);
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->rule(RecastingRemovalRector::class);
    $rectorConfig->rule(RemoveConcatAutocastRector::class);
    $rectorConfig->rule(SymplifyQuoteEscapeRector::class);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);
};