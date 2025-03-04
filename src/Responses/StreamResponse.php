<?php

namespace Gingdev\Qwen\Responses;

use Gingdev\Qwen\Contracts\StreamResponseContract;
use Symfony\Component\HttpClient\Chunk\ServerSentEvent;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * @template T
 *
 * @implements StreamResponseContract<T>
 */
final class StreamResponse implements StreamResponseContract
{
    /**
     * @param class-string<T> $responseClass
     */
    public function __construct(
        private ResponseStreamInterface $response,
        private string $responseClass,
    ) {
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->response as $chunk) {
            if ($chunk instanceof ServerSentEvent) {
                yield $this->responseClass::from($chunk->getArrayData()['choices'][0]['delta']);
            }
        }
    }
}
