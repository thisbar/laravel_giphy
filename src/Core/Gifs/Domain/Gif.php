<?php

declare(strict_types=1);

namespace LaravelGiphy\Core\Gifs\Domain;

use LaravelGiphy\Shared\Domain\Gifs\GifId;

final class Gif
{
	public function __construct(
		private GifId $id,
		private GifTitle $title,
		private GifUrl $url,
	) {}


	public function id(): string
	{
		return $this->id->value();
	}

	public function title(): string
	{
		return $this->title->value();
	}

	public function url(): string
	{
		return $this->url->value();
	}

	public function toArray(): array
	{
		return [
			'id'    => $this->id->value(),
			'title' => $this->title->value(),
			'url'   => $this->url->value(),
		];
	}
}
