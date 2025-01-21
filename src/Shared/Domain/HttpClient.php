<?php

declare(strict_types=1);

namespace LaravelGiphy\Shared\Domain;

interface HttpClient
{
	public function get(string $url, array $query = []): array;
	public function post(string $url, array $data = []): array;
}
