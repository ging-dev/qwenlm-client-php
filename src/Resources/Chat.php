<?php

namespace Gingdev\Qwen\Resources;

use Gingdev\Qwen\Concerns\HasHttpClient;
use Gingdev\Qwen\Concerns\Preparable;
use Gingdev\Qwen\Responses\Chat\CreateMessage;
use Gingdev\Qwen\Responses\Chat\CreateResponse;
use Gingdev\Qwen\Responses\StreamResponse;
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
     * @return StreamResponse<CreateMessage>
     */
    public function createStreamed(array $parameters): StreamResponse
    {
        $eventSourceClient = new EventSourceHttpClient($this->client);
        $response = $eventSourceClient->connect('api/chat/completions', [
            'json' => $this->prepare($parameters, true),
        ], 'POST');

        return new StreamResponse($eventSourceClient->stream($response), CreateMessage::class);
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
