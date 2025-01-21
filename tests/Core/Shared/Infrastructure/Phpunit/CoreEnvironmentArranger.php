<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Core\Shared\Infrastructure\Phpunit;

use Database\Seeders\PassportSeeder;
use Database\Seeders\UserSeeder;
use Doctrine\ORM\EntityManager;
use Elastic\Elasticsearch\Client as ElasticsearchClient;
use LaravelGiphy\Tests\Shared\Infrastructure\Arranger\EnvironmentArranger;
use LaravelGiphy\Tests\Shared\Infrastructure\Doctrine\MySqlDatabaseCleaner;
use LaravelGiphy\Tests\Shared\Infrastructure\Elastic\ElasticDatabaseCleaner;

use function Lambdish\Phunctional\apply;

final readonly class CoreEnvironmentArranger implements EnvironmentArranger
{
	public function __construct(private ElasticsearchClient $elasticsearchClient, private EntityManager $entityManager) {}

	public function arrange(): void
	{
		apply(new ElasticDatabaseCleaner(), [$this->elasticsearchClient]);
		apply(new MySqlDatabaseCleaner(), [$this->entityManager]);
		apply(new PassportSeeder());
	}

	public function close(): void
	{
		apply(new UserSeeder($this->entityManager));
	}
}
