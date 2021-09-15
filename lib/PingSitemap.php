<?php
namespace App\Addons;

class PingSitemap extends Base
{
    public function after_post($post)
    {
        $result = false;

        $permalink = $post->permalink;

        if (!$permalink) {
            return false;
        }

        $urls = [
            'google' => 'https://www.google.com/ping?sitemap={sitemap}',
            'bing' => 'https://www.bing.com/ping?sitemap={sitemap}',
        ];

        foreach ($urls as $search_engine => $url) {
            $sitemap = $this->getSitemap($post);
            $url = str_replace('{sitemap}', $sitemap, $url);
            echo "\n🕷 Pinging " . $url;
            $result = file_get_contents($url);
        }

        return true;
    }

    public function getSitemap($post)
    {
        $scheme = parse_url($post->permalink, PHP_URL_SCHEME);
        $host = parse_url($post->permalink, PHP_URL_HOST);

        return $scheme . '://' . $host . '/sitemap.xml';
    }
}
