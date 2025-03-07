<?php

namespace Gingdev\Qwen;

use Gingdev\Qwen\Resources\Chat;
use Gingdev\Qwen\Resources\Image;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client
{
    private function __construct(
        private HttpClientInterface $client,
    ) {
    }

    /**
     * Creates a new Client instance using the provided bearer token.
     *
     * Configures an HTTP client with a base URI of "https://chat.qwen.ai/", sets bearer authentication 
     * with the given token, and adds a specific cookie header.
     *
     * @param string $token Bearer token for authentication.
     * @return self A new Client instance.
     */
    public static function fromToken(string $token): self
    {
        return new self(HttpClient::createForBaseUri('https://chat.qwen.ai/', [
            'auth_bearer' => $token,
            'headers' => ['Cookie' => 'ssxmod_itna=Symfony'],
        ]));
    }

    /**
     * Returns a Chat resource instance.
     *
     * This method creates a Chat instance bound to the current HTTP client configuration,
     * enabling access to chat-related functionalities.
     *
     * @return Chat The Chat resource instance.
     */
    public function chat(): Chat
    {
        return $this->bindForClass(Chat::class);
    }

    public function image(): Image
    {
        return $this->bindForClass(Image::class);
    }

    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    private function bindForClass(string $class): mixed
    {
        return (\Closure::bind(function (string $class, HttpClientInterface $client): mixed {
            $instance = new $class();
            $instance->client = $client;

            return $instance;
        }, null, $class))($class, $this->client);
    }
}
