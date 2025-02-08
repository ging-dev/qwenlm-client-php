<?php

namespace Gingdev\Qwen\Resources;

use Gingdev\Qwen\Concerns\HasHttpClient;
use Gingdev\Qwen\Concerns\Preparable;
use Gingdev\Qwen\Responses\Image\CreateImage;

final class Image
{
    use HasHttpClient;
    use Preparable;

    public function generate(string $prompt): CreateImage
    {
        $response = $this->client->request('POST', 'api/chat/completions', [
            'json' => $this->prepare([
                'model' => 'qwen-max-latest',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                        'chat_type' => 't2i',
                    ],
                ],
                'size' => '1024*1024',
            ], false),
        ]);
        $taskId = $response->toArray()['messages'][1]['extra']['wanx']['task_id'];
        $result = '';
        do {
            $result = $this->client->request(
                'GET',
                sprintf('api/v1/tasks/status/%s', $taskId)
            )->toArray()['content'];
        } while (!$result);

        return CreateImage::from($result);
    }
}
