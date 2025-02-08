<?php

namespace Gingdev\Qwen\Concerns;

use Symfony\Contracts\HttpClient\HttpClientInterface;

trait HasHttpClient
{
    private HttpClientInterface $client;
}
