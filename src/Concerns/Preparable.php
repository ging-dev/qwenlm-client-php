<?php

namespace Gingdev\Qwen\Concerns;

use Symfony\Component\Uid\Uuid;

/**
 * @phpstan-type ParametersType array{model: string, messages: list<array{role: 'assistant'|'system'|'user', content: string, chat_type: string}> }
 */
trait Preparable
{
    /**
     * @param ParametersType $parameters
     *
     * @return array{chat_id: string, id: string, session_id: string, stream: bool, ...}
     */
    private function prepare(array $parameters, bool $stream): array
    {
        return array_merge($parameters, [
            'chat_id' => 'local',
            'id' => Uuid::v4()->toString(),
            'session_id' => Uuid::v4()->toString(),
            'stream' => $stream,
        ]);
    }
}
