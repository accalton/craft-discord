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

        $emojis = DiscordBot::getInstance()->request->processRequest($url);

        $return = [];
        foreach ($emojis as $emoji) {
            $return[$emoji->name] = $emoji->id;
        }

        return $return;
    }

    public function roles()
    {
        $url = $this->url . '/roles';

        $roles = DiscordBot::getInstance()->request->processRequest($url);

        $return = [];
        foreach ($roles as $role) {
            $return[$role->name] = $role->id;
        }

        return $return;
    }
}