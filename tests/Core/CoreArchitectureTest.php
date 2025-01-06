<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Core;

use LaravelGhipy\Tests\Shared\Infrastructure\ArchitectureTest;
use PHPat\Selector\Selector;
use PHPat\Test\Builder\Rule;
use PHPat\Test\PHPat;

final class CoreArchitectureTest
{
	public function test_domain_should_only_import_itself_and_shared(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('/^LaravelGhipy\\\\Core\\\\.+\\\\Domain/', true))
			->canOnlyDependOn()
			->classes(...array_merge(ArchitectureTest::languageClasses(), [
				// Itself
				Selector::inNamespace('/^LaravelGhipy\\\\Core\\\\.+\\\\Domain/', true),
				// Shared
				Selector::inNamespace('LaravelGhipy\Shared\Domain'),
			]))
			->because('domain layer can only import itself and shared domain');
	}

	public function test_application_should_only_import_itself_and_domain(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('/^LaravelGhipy\\\\Core\\\\.+\\\\Application/', true))
			->canOnlyDependOn()
			->classes(...array_merge(ArchitectureTest::languageClasses(), [
				// Itself
				Selector::inNamespace('/^LaravelGhipy\\\\Core\\\\.+\\\\Application/', true),
				Selector::inNamespace('/^LaravelGhipy\\\\Core\\\\.+\\\\Domain/', true),
				// Shared
				Selector::inNamespace('LaravelGhipy\Shared'),
			]))
			->because('application layer can only import itself and shared');
	}

	public function test_infrastructure_should_not_import_other_contexts_beside_shared(): Rule
	{
		return PHPat::rule()
			->classes(Selector::inNamespace('LaravelGhipy\Core'))
			->shouldNotDependOn()
			->classes(Selector::inNamespace('LaravelGhipy'))
			->excluding(
				// Itself
				Selector::inNamespace('LaravelGhipy\Core'),
				// Shared
				Selector::inNamespace('LaravelGhipy\Shared'),
			);
	}
}
