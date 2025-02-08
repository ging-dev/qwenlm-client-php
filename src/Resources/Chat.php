<?php

namespace Gingdev\Qwen\Resources;

use Gingdev\Qwen\Concerns\HasHttpClient;
use Gingdev\Qwen\Concerns\Preparable;
use Gingdev\Qwen\Responses\Chat\CreateMessage;
use Gingdev\Qwen\Responses\Chat\CreateResponse;
use Symfony\Component\HttpClient\Chunk\ServerSentEvent;
use Symfony\Component\HttpClient\EventSourceHttpClient;

/**
 * @phpstan-import-type ParametersType from Preparable
 */
final class Chat
{
    use HasHttpClient;
    use Preparable;

    /**
     * @param ParametersType $parameters
     *
     * @return iterable<CreateMessage>
     */
    public function createStreamed(array $parameters): iterable
    {
        $eventSourceClient = new EventSourceHttpClient($this->client);
        $response = $eventSourceClient->connect('api/chat/completions', [
            'json' => $this->prepare($parameters, true),
        ], 'POST');
        $prev = '';
        foreach ($eventSourceClient->stream($response) as $chunk) {
            if ($chunk instanceof ServerSentEvent) {
                /** @var array{choices: array{0: array{delta: array{role: string, content: string, extra?: mixed[]}}}} */
                $data = $chunk->getArrayData();
                $message = $data['choices'][0]['delta'];
                $content = $message['content'];
                $message['content'] = substr($content, \strlen($prev));
                $prev = $content;
                yield CreateMessage::create($message);
            }
        }
    }

    /**
     * @param ParametersType $parameters
     */
    public function create(array $parameters): CreateResponse
    {
        return CreateResponse::from($this->client->request('POST', 'api/chat/completions', [
            'json' => $this->prepare($parameters, false),
        ])->toArray());
    }
}
