<?php
namespace App\Addons;

class NerdIndex extends Base
{
    public function after_post($post)
    {
        $result = false;

        $permalink = $post->permalink;

        if (!$permalink) {
            return false;
        }

        echo "\n" . '🕸 Indexing: ' . $permalink;

        $nerd = [];
        $nerd['email'] = 'edy.tungu@gmail.com';
        $nerd['api_key'] = 'dTgQphtIHhBfu5jmKLKq4IYs2';
        $nerd['url'] = $permalink;

        $host = 'https://nerd.dojo.cc';

        $endpoint = $host . '/api/urls';

        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($nerd),
            ],
        ];

        $context = stream_context_create($opts);

        $result = file_get_contents($endpoint, false, $context);

        return $result;
    }
}
