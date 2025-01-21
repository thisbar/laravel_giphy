<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Gifs\Domain;

use InvalidArgumentException;

final class GifCollection
{
	private array $gifs;

	public function __construct(array $gifs = [])
	{
		foreach ($gifs as $gif) {
			if (!$gif instanceof Gif) {
				throw new InvalidArgumentException('All items must be instances of Gif');
			}
		}

		$this->gifs = $gifs;
	}

	public function add(Gif $gif): void
	{
		$this->gifs[] = $gif;
	}

	public function all(): array
	{
		return $this->gifs;
	}

	public function toArray(): array
	{
		return array_map(fn (Gif $gif) => $gif->toArray(), $this->gifs);
	}
}
