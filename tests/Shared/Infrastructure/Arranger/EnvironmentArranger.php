<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Shared\Infrastructure\Arranger;

interface EnvironmentArranger
{
	public function arrange(): void;

	public function close(): void;
}
