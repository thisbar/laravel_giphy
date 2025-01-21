<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Shared\Infrastructure\Mink;

use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\DomCrawler\Crawler;

final readonly class MinkSessionRequestHelper
{
	public function __construct(private MinkHelper $sessionHelper) {}

	public function sendRequest(string $method, string $url, array $optionalParams = []): void
	{
		$this->request($method, $url, $optionalParams);
	}

	public function sendRequestWithPyStringNode(
		string $method,
		string $url,
		PyStringNode $body,
		array $optionalParams = []
	): void {
		$this->request($method, $url, array_merge($optionalParams, ['content' => $body->getRaw()]));
	}

	public function request(string $method, string $url, array $optionalParams = []): Crawler
	{
		return $this->sessionHelper->sendRequest($method, $url, $optionalParams);
	}
}
