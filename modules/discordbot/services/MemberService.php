<?php

namespace discordbot\services;

use discordbot\DiscordBot;

class MemberService
{
    public function guilds()
    {
        $url = 'users/@me/guilds';
        return DiscordBot::getInstance()->request->send($url);
    }
}