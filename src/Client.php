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

    public static function fromToken(string $token): self
    {
        return new self(HttpClient::createForBaseUri('https://chat.qwenlm.ai/', [
            'auth_bearer' => $token,
        ]));
    }

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
