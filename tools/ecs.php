<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Tools\CodeStyle\CodingStyle;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        realpath(__DIR__ . '/../src'),
        realpath(__DIR__ . '/../tests'),
    ]);

    $ecsConfig->sets([CodingStyle::DEFAULT]);
};
