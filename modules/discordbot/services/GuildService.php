<?php

namespace discordbot\services;

use Craft;
use discordbot\DiscordBot;

class GuildService
{
    private $guildId = '990803597547679745';
    private $url;

    public function __construct()
    {
        $this->url = 'guilds/' . $this->guildId;
    }

    public function emojis()
    {
        $url = $this->url . '/emojis';

        $emojis = DiscordBot::getInstance()->request->send($url);

        $return = [];
        foreach ($emojis as $emoji) {
            $return[$emoji->id] = $emoji->name;
        }

        asort($return);

        return $return;
    }

    public function roles()
    {
        $url = $this->url . '/roles';

        $roles = DiscordBot::getInstance()->request->send($url);

        $return = [];
        foreach ($roles as $role) {
            $return[$role->id] = $role->name;
        }

        asort($return);

        return $return;
    }
}