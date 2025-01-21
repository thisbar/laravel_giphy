<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Shared\Infrastructure\PhpUnit;

use Elastic\Elasticsearch\Client;
use LaravelGiphy\Tests\TestCase;

abstract class InfrastructureTestCase extends TestCase
{
	protected function service(string $id): mixed
	{
		return $this->createApplication()->make($id);
	}

	public function refreshElasticIndex(string $index): void
	{
		$this->service(Client::class)->indices()->refresh(['index' => $index]);
	}
}
