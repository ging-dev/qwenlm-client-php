<?php

namespace Gingdev\Qwen\Responses\Chat;

/**
 * @internal
 */
final class CreateResponse
{
    private function __construct(
        public CreateMessage $message,
    ) {
    }

    /**
     * @param array{choices: array<int, array{message: array{role: string, content: string}}>} $data
     */
    public static function from(array $data): self
    {
        return new self(CreateMessage::create($data['choices'][0]['message']));
    }
}
