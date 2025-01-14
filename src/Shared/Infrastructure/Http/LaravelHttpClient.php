<?php

declare(strict_types=1);

namespace LaravelGhipy\Shared\Infrastructure\Http;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LaravelGhipy\Shared\Domain\HttpClient;

final class LaravelHttpClient implements HttpClient
{
	public function get(string $url, array $query = []): array
	{
		$response = Http::get($url, $query);

		return $this->handleResponse($response);
	}

	public function post(string $url, array $data = []): array
	{
		$response = Http::post($url, $data);

		return $this->handleResponse($response);
	}

	private function handleResponse(Response $response): array
	{
		return $response->json();
	}
}
