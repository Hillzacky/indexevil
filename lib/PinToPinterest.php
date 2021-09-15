<?php
namespace App\Addons;

use seregazhuk\PinterestBot\Factories\PinterestBot;
use App\Pinterest;

class PinToPinterest extends Base
{
    public $enable = false;

    public $post;

    public function start($params)
    {
        echo "\n" . '📌 Importing pinterest accounts...';
        $this->import();

        return true;
    }

    public function after_post($post)
    {
        $this->post = $post;

        $permalink = $post->permalink;

        if (!$permalink) {
            return false;
        }

        echo "\n" . '📌 Pinning: ' . $permalink;

        $bot = $this->getRandomAccount();

        $image = collect($post->ingredients['images'])->random()['url'];
        $boardId = $this->getOrCreateBoard($bot);

        $result = $bot->pins->create(
            $image,
            $boardId,
            $post->title,
            $post->permalink
        );

        if (isset($result['id'])) {
            echo "\n" .
                '📌 Pinned to: https://pinterest.com/pin/' .
                $result['id'] .
                "\n";

            return true;
        }

        return false;
    }

    public function getOrCreateBoard($bot, $tries = 0)
    {
        $board_names = [
            'Ocean of Miracles',
            'Loop n Enjoy',
            'Cats Gone Wild',
            'Live Longer Party Stronger',
            'Foodie Gone Crazy',
            'Make It Count Dad',
            'Fuzzy Things I Adore',
            'My Life My Finger',
            'Catch It Assilicious',
            'Gone With The Money',
            'Fantasy',
            'Bribe The Masters',
            'Pinz',
            'Pinstormz',
            'Poopinsonpool',
            'Dress Tasterz',
            'This is My 18th Drink',
            'Fake It With Quotes',
            'Kingdom',
            'Things I Desire',
            'Itzz',
            'Ultimate Guide To Fun',
            'All-Time Best Pinterest Board',
            'Random Stuffs',
            'All the Small Things',
            'Interesting',
            'Board',
            'Bored',
        ];

        $boards = $bot->boards->forMe();

        if (empty($boards)) {
            $name = collect($board_names)->random();
            $description = collect(
                $this->post->ingredients['sentences']
            )->random();

            echo "\n" . '📌 Creating board: ' . $name;
            $result = $bot->boards->create($name, $description);
            sleep(3);
            $tries++;

            if ($tries < 3) {
                return $this->getOrCreateBoard($bot, $tries);
            }
            return false;
        }

        return collect($boards)->random()['id'];
    }

    public function getRandomAccount()
    {
        $pinterest = Pinterest::inRandomOrder()
            ->where('banned', false)
            ->first();

        if (is_null($pinterest)) {
            echo "\n" .
                "📌 All pinterest accounts are banned. Please update app/Addons/pinterest.txt\n";
            return false;
        }

        $bot = PinterestBot::create();
        $logged_in = $bot->auth->login($pinterest->email, $pinterest->password);

        if ($logged_in) {
            return $bot;
        }

        $pinterest->banned = true;
        $pinterest->save();

        return $this->getRandomAccount();
    }

    public function import()
    {
        $pinterests = [];
        $text = trim(file_get_contents(dirname(__FILE__) . '/pinterest.txt'));

        $accounts = explode("\n", $text);

        foreach ($accounts as $key => $account) {
            $account = trim($account);

            $account = explode(' ', $account);

            $pinterest = new Pinterest();
            $pinterest->email = trim($account[0]);
            $pinterest->password = trim($account[1]);

            $bot = PinterestBot::create();

            echo "\n" .
                '📌 Importing account #' .
                ($key + 1) .
                ': ' .
                $pinterest->email;

            $pinterest->save();
        }
    }
}
