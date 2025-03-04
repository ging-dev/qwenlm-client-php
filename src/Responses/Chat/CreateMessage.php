<?php

namespace Gingdev\Qwen\Responses\Chat;

/**
 * @phpstan-type MessageType array{role: string, content: string, extra?: mixed}
 *
 * @internal
 */
final class CreateMessage
{
    /**
     * @param mixed[]|null $extra
     */
    private function __construct(
        public string $role,
        public string $content,
        public ?array $extra,
    ) {
    }

    /**
     * @param MessageType $data
     */
    public static function from(array $data): self
    {
        return new self($data['role'], $data['content'], $data['extra'] ?? null);
    }
}
