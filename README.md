```php
<?php

require __DIR__.'/vendor/autoload.php';

use Gingdev\Qwen\Client;

$client = Client::fromToken('...');

echo $client->chat()->create([
    'model' => 'qwen-max-latest',
    'messages' => [
        ['role' => 'user', 'content' => 'Who is Yugi?', 'chat_type' => 'search'],
    ]
]);
```
