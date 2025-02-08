<?php

namespace Gingdev\Qwen\Responses\Image;

/**
 * @internal
 */
final class CreateImage
{
    private function __construct(
        public string $url,
    ) {
    }

    public static function from(string $url): self
    {
        return new self($url);
    }
}
