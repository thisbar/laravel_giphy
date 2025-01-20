<?php

declare(strict_types=1);

namespace LaravelGhipy\Tests\Shared\Infrastructure\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use DateTimeImmutable;
use Illuminate\Contracts\Foundation\Application;
use Laravel\Passport\PersonalAccessTokenFactory;
use LaravelGhipy\Core\Auth\Infrastructure\TokenGenerator;
use LaravelGhipy\Core\Users\Application\UserEmailSearcher;
use LaravelGhipy\Core\Users\Domain\Email;
use LaravelGhipy\Core\Users\Domain\UserRepository;
use LaravelGhipy\Tests\Shared\Infrastructure\Mink\MinkHelper;
use LaravelGhipy\Tests\Shared\Infrastructure\Mink\MinkSessionRequestHelper;
use RuntimeException;

final class ApiContext extends RawMinkContext
{
	private ?MinkHelper $sessionHelper         = null;
	private ?MinkSessionRequestHelper $request = null;
	private Application $app;
	private UserEmailSearcher $userSearcher;
	private TokenGenerator $authTokenGenerator;
	private string $validToken;

	/**
	 * @BeforeScenario
	 */
	public function setUp(): void
	{
		$this->app = require __DIR__ . '/../../../../bootstrap/app.php';
		$this->app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

		$this->userSearcher       = new UserEmailSearcher(app(UserRepository::class));
		$this->authTokenGenerator = new TokenGenerator(app(PersonalAccessTokenFactory::class));
	}

	/**
	 * @BeforeScenario @auth
	 */
	public function prepareAuthScenario(): void
	{
		$user = $this->userSearcher->search(Email::from('test@test.com'));

		$token            = $this->authTokenGenerator->generate($user);
		$this->validToken = $token['token'];
	}

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
	 * @Given I send a :method request to :url with headers:
	 */
	public function iSendARequestToWithHeaders(string $method, string $url, TableNode $headers): void
	{
		$headerArray = [];
		foreach ($headers->getRowsHash() as $key => $value) {
			$headerArray[$key] = $this->replacePlaceholders($value);
		}

		$this->getRequestHelper()->sendRequest($method, $this->locatePath($url), ['server' => $headerArray]);
	}

	/**
	 * @Given I send a :method request to :url as an authenticated user
	 */
	public function iSendARequestToAsAnAuthenticatedUser(string $method, string $url): void
	{
		$authorizationHeader = ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->validToken];

		$this->getRequestHelper()->sendRequest($method, $this->locatePath($url), ['server' => $authorizationHeader]);
	}

	/**
	 * @Given I send a :method request to :url as an authenticated user with body:
	 */
	public function iSendARequestToAsAnAuthenticatedUserWithBody(string $method, string $url, PyStringNode $body): void
	{
		$authorizationHeader = ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->validToken];

		$this->getRequestHelper()->sendRequestWithPyStringNode(
			$method,
			$this->locatePath($url),
			$body,
			['server' => $authorizationHeader]
		);
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

	/**
	 * @Then the response content should match JSON:
	 */
	public function theResponseContentShouldMatchJson(PyStringNode $expectedResponse): void
	{
		$expected = $this->decodeJson($expectedResponse->getRaw());
		$actual   = $this->decodeJson($this->getSessionHelper()->getResponse());

		$this->validateResponse($expected, $actual);
	}

	private function validateResponse(array $expected, array $actual): void
	{
		foreach ($expected as $key => $value) {
			if ($this->shouldValidateRecursively($key, $value)) {
				$this->validateNestedData($value, $actual[$key] ?? []);
				continue;
			}

			$this->validateKeyValue($key, $value, $actual[$key] ?? null);
		}
	}

	private function shouldValidateRecursively(string $key, mixed $value): bool
	{
		return $key === 'data' && is_array($value);
	}

	private function validateNestedData(array $expectedData, array $actualData): void
	{
		foreach ($expectedData as $subKey => $subValue) {
			$this->validateKeyValue($subKey, $subValue, $actualData[$subKey] ?? null);
		}
	}

	private function decodeJson(string $json): array
	{
		return json_decode(trim($json), true, 512, JSON_THROW_ON_ERROR);
	}

	private function validateKeyValue(string $key, mixed $expectedValue, mixed $actualValue): void
	{
		if ($this->isDynamicPlaceholder($expectedValue)) {
			$this->validateDynamicValue($key, $expectedValue, $actualValue);
			return;
		}

		if ($expectedValue !== $actualValue) {
			$this->throwKeyMismatchException($key, $expectedValue, $actualValue);
		}
	}

	private function replacePlaceholders(string $value): string
	{
		if (str_contains($value, '<validToken>')) {
			return str_replace('<validToken>', $this->validToken, $value);
		}

		return $value;
	}

	private function isDynamicPlaceholder(mixed $value): bool
	{
		return is_string($value) && str_starts_with($value, '<') && str_ends_with($value, '>');
	}

	private function validateDynamicValue(string $key, string $placeholder, mixed $actualValue): void
	{
		$validators = [
			'<string>'   => fn (mixed $value): bool => is_string($value),
			'<datetime>' => fn (string $value): bool => $this->isValidDatetime($value),
		];

		if (!isset($validators[$placeholder]) || !$validators[$placeholder]($actualValue)) {
			$this->throwInvalidPlaceholderException($key, $placeholder, $actualValue);
		}
	}

	private function isValidDatetime(string $datetime): bool
	{
		return (bool) DateTimeImmutable::createFromFormat(config('passport.datetime_format'), $datetime);
	}

	private function throwKeyMismatchException(string $key, mixed $expected, mixed $actual): void
	{
		throw new RuntimeException(
			sprintf("The key '%s' does not match.\nExpected: %s\nActual: %s", $key, json_encode($expected), json_encode($actual))
		);
	}

	private function throwInvalidPlaceholderException(string $key, string $placeholder, mixed $actualValue): void
	{
		throw new RuntimeException(
			sprintf("Expected '%s' to match '%s'. Actual: %s", $key, $placeholder, json_encode($actualValue))
		);
	}


	private function sanitizeOutput(string $output): false | string
	{
		return json_encode(json_decode(trim($output), true, 512, JSON_THROW_ON_ERROR), JSON_THROW_ON_ERROR);
	}
}
