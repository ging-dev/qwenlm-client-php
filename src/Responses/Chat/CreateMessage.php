<?php

namespace Gingdev\Qwen\Responses\Chat;

/**
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
     * @param array{role: string, content: string, extra?: mixed[]} $data
     */
    public static function create(array $data): self
    {
        return new self($data['role'], $data['content'], $data['extra'] ?? null);
    }
}
