<?php

namespace discordbot\services;

use discordbot\DiscordBot;

class MemberService
{
    public function guilds()
    {
        $url = 'users/@me/guilds';

        $guilds = DiscordBot::getInstance()->request->send($url);

        usort($guilds, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return array_values($guilds);
    }
}