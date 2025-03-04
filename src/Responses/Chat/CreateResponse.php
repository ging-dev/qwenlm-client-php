<?php

namespace Gingdev\Qwen\Responses\Chat;

/**
 * @internal
 *
 * @phpstan-import-type MessageType from CreateMessage
 */
final class CreateResponse
{
    private function __construct(
        public CreateMessage $message,
    ) {
    }

    /**
     * @param array{choices: array<int, array{message: MessageType}>} $data
     */
    public static function from(array $data): self
    {
        return new self(CreateMessage::from($data['choices'][0]['message']));
    }
}
