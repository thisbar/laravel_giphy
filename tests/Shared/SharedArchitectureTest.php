<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Shared;

use LaravelGhipy\Tests\Shared\Infrastructure\ArchitectureTest;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class SharedArchitectureTest
{
    public function test_shared_domain_should_not_import_from_outside(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('LaravelGhipy\Shared\Domain'))
            ->canOnlyDependOn()
            ->classes(...array_merge(ArchitectureTest::languageClasses(), [
                // Itself
                Selector::inNamespace('LaravelGhipy\Shared\Domain'),
            ]))
            ->because('shared domain cannot import from outside');
    }

    public function test_shared_infrastructure_should_not_import_from_other_contexts(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('LaravelGhipy\Shared\Infrastructure'))
            ->shouldNotDependOn()
            ->classes(Selector::inNamespace('LaravelGhipy'))
            ->excluding(
            // Itself
                Selector::inNamespace('LaravelGhipy\Shared'),
            );
    }

    public function test_all_use_cases_can_only_have_one_public_method(): Rule
    {
        return PHPat::rule()
            ->classes(
                Selector::classname('/^LaravelGhipy\\\\.+\\\\.+\\\\LaravelGhipy\Application\\\\.+\\\\.*$/', true)
            )
            ->excluding(
                Selector::inNamespace('/.*\\\\Tests\\\\.*/', true)
            )
            ->shouldHaveOnlyOnePublicMethod();
    }
}
