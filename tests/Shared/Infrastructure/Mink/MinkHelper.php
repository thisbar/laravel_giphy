<?php

declare(strict_types=1);

namespace LaravelGiphy\Tests\Shared\Infrastructure\Mink;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Session;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

final readonly class MinkHelper
{
	public function __construct(private Session $session) {}

	public function sendRequest(string $method, string $url, array $optionalParams = []): Crawler
	{
		$defaultOptionalParams = [
			'parameters'    => [],
			'files'         => [],
			'server'        => ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'],
			'content'       => null,
			'changeHistory' => true,
		];

		if (isset($optionalParams['content'])) {
			$content = $optionalParams['content'];
			unset($optionalParams['content']);
		}

		$optionalParams            = array_merge_recursive($defaultOptionalParams, $optionalParams);
		$optionalParams['content'] = $content ?? null;

		$crawler = $this->getClient()->request(
			$method,
			$url,
			$optionalParams['parameters'],
			$optionalParams['files'],
			$optionalParams['server'],
			$optionalParams['content'],
			$optionalParams['changeHistory']
		);

		return $crawler;
	}

	public function getResponse(): string
	{
		return $this->getSession()->getPage()->getContent();
	}

	public function getResponseHeaders(): array
	{
		return $this->normalizeHeaders(array_change_key_case($this->getSession()->getResponseHeaders(), CASE_LOWER));
	}

	public function resetServerParameters(): void
	{
		$this->getClient()->setServerParameters([]);
	}

	public function getRequest(): object
	{
		return $this->getClient()->getRequest();
	}

	private function getSession(): Session
	{
		return $this->session;
	}

	private function getDriver(): DriverInterface
	{
		return $this->getSession()->getDriver();
	}

	private function getClient(): AbstractBrowser
	{
		return $this->getDriver()->getClient();
	}

	private function normalizeHeaders(array $headers): array
	{
		return array_map('implode', array_filter($headers));
	}
}
