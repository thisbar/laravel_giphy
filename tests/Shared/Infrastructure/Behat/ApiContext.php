<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Shared\Infrastructure\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\RawMinkContext;
use LaravelGhipy\Tests\Shared\Infrastructure\Mink\MinkHelper;
use LaravelGhipy\Tests\Shared\Infrastructure\Mink\MinkSessionRequestHelper;
use RuntimeException;

final class ApiContext extends RawMinkContext
{
	private ?MinkHelper $sessionHelper         = null;
	private ?MinkSessionRequestHelper $request = null;

	private function getSessionHelper(): MinkHelper
	{
		if ($this->sessionHelper === null) {
			$this->sessionHelper = new MinkHelper($this->getSession());
		}

		return $this->sessionHelper;
	}

	private function getRequestHelper(): MinkSessionRequestHelper
	{
		if ($this->request === null) {
			$this->request = new MinkSessionRequestHelper($this->getSessionHelper());
		}

		return $this->request;
	}

	/**
	 * @Given I send a :method request to :url
	 */
	public function iSendARequestTo(string $method, string $url): void
	{
		$this->getRequestHelper()->sendRequest($method, $this->locatePath($url));
	}

	/**
	 * @Given I send a :method request to :url with body:
	 */
	public function iSendARequestToWithBody(string $method, string $url, PyStringNode $body): void
	{
		$this->getRequestHelper()->sendRequestWithPyStringNode($method, $this->locatePath($url), $body);
	}

	/**
	 * @Then the response content should be:
	 */
	public function theResponseContentShouldBe(PyStringNode $expectedResponse): void
	{
		$expected = $this->sanitizeOutput($expectedResponse->getRaw());
		$actual   = $this->sanitizeOutput($this->getSessionHelper()->getResponse());

		if ($expected === false || $actual === false) {
			throw new RuntimeException('The outputs could not be parsed as JSON');
		}

		if ($expected !== $actual) {
			throw new RuntimeException(
				sprintf("The outputs does not match!\n\n-- Expected:\n%s\n\n-- Actual:\n%s", $expected, $actual)
			);
		}
	}

	/**
	 * @Then the response should be empty
	 */
	public function theResponseShouldBeEmpty(): void
	{
		$actual = trim($this->getSessionHelper()->getResponse());

		if (!empty($actual)) {
			throw new RuntimeException(sprintf("The outputs is not empty, Actual:\n%s", $actual));
		}
	}

	/**
	 * @Then print last api response
	 */
	public function printApiResponse(): void
	{
		print_r($this->getSessionHelper()->getResponse());
	}

	/**
	 * @Then print response headers
	 */
	public function printResponseHeaders(): void
	{
		print_r($this->getSessionHelper()->getResponseHeaders());
	}

	/**
	 * @Then the response status code should be :expectedResponseCode
	 */
	public function theResponseStatusCodeShouldBe(mixed $expectedResponseCode): void
	{
		$actualStatusCode = $this->getSession()->getStatusCode();

		if ($actualStatusCode !== (int) $expectedResponseCode) {
			throw new RuntimeException(
				sprintf('The status code <%s> does not match the expected <%s>', $actualStatusCode, $expectedResponseCode)
			);
		}
	}

	private function sanitizeOutput(string $output): false | string
	{
		return json_encode(json_decode(trim($output), true, 512, JSON_THROW_ON_ERROR), JSON_THROW_ON_ERROR);
	}
}
