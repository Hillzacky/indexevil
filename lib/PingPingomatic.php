<?php
namespace App\Addons;

class PingPingomatic extends Base
{
    public function after_post($post)
    {
        $result = false;

        $permalink = $post->permalink;

        if (!$permalink) {
            return false;
        }

        $url =
            'https://pingomatic.com/ping/?title={title}&blogurl={blogurl}&rssurl={rssurl}&chk_blogs=on&chk_feedburner=on&chk_tailrank=on&chk_superfeedr=on';

        $url = str_replace('{title}', urlencode($post->title), $url);
        $url = str_replace(
            '{blogurl}',
            urlencode($this->getBlogUrl($post)),
            $url
        );
        $url = str_replace(
            '{rssurl}',
            urlencode($this->getRssUrl($post)),
            $url
        );

        file_get_contents($url);

        return true;
    }

    public function getBlogUrl($post)
    {
        $scheme = parse_url($post->permalink, PHP_URL_SCHEME);
        $host = parse_url($post->permalink, PHP_URL_HOST);

        return $scheme . '://' . $host . '/';
    }

    public function getRssUrl($post)
    {
        return $this->getBlogUrl($post) . 'feeds/posts/default?alt=rss';
    }
}
