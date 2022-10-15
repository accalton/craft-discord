<?php

namespace magi\services;

use Craft;
use GuzzleHttp\Exception\ClientException;
use magi\Magi;

class GuildService
{
    public function channels($guildId)
    {
        return Magi::getInstance()->request->send('GET', 'guilds/' . $guildId . '/channels');
    }

    public function emojis($guildId)
    {
        $emojis = Magi::getInstance()->request->send('GET', 'guilds/' . $guildId . '/emojis');

        usort($emojis, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return $emojis;
    }

    public function roles($guildId)
    {
        $roles = Magi::getInstance()->request->send('GET', 'guilds/' . $guildId . '/roles');

        usort($roles, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return $roles;
    }

    public function textChannels($guildId)
    {
        $channels = $this->channels($guildId);

        $channels = array_filter($channels, function ($channel) {
            return $channel->type === 0;
        });

        usort($channels, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return $channels;
    }
}