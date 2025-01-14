<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Tools\CodeStyle\CodingStyle;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/../src',
        __DIR__ . '/../tests',
    ]);

    $ecsConfig->sets([CodingStyle::ALIGNED]);

    $ecsConfig->skip([
        FinalClassFixer::class => [
            __DIR__ . '/../src/Core/Gifs/Domain/Search/SearchResult.php'
        ]
    ]);
};
