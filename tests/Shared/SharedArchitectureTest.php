<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Shared;

use LaravelGiphy\Tests\Shared\Infrastructure\ArchitectureTest;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;
use Ramsey\Uuid\Uuid;

final class SharedArchitectureTest
{
	public function test_shared_domain_should_not_import_from_outside(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('LaravelGiphy\Shared\Domain'))
			->canOnlyDependOn()
			->classes(...array_merge(ArchitectureTest::languageClasses(), [
				// Itself
				Selector::inNamespace('LaravelGiphy\Shared\Domain'),
				// Dependencies treated as domain
				Selector::classname(Uuid::class),
			]))
			->because('shared domain cannot import from outside');
	}

	public function test_shared_infrastructure_should_not_import_from_other_contexts(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('LaravelGiphy\Shared\Infrastructure'))
			->shouldNotDependOn()
			->classes(Selector::inNamespace('LaravelGiphy'))
			->excluding(
				// Itself
				Selector::inNamespace('LaravelGiphy\Shared'),
			);
	}

	public function test_all_use_cases_can_only_have_one_public_method(): Rule
	{
		return PHPat::rule()
			->classes(Selector::classname('/^LaravelGiphy\\\\.+\\\\.+\\\\LaravelGiphy\Application\\\\.+\\\\.*$/', true))
			->excluding(Selector::inNamespace('/.*\\\\Tests\\\\.*/', true))
			->shouldHaveOnlyOnePublicMethod();
	}
}
