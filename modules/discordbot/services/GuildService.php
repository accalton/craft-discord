<?php

namespace discordbot\services;

use Craft;
use discordbot\DiscordBot;

class GuildService
{
    public function channels($guildId)
    {
        $url = 'guilds/' . $guildId . '/channels';

        $channels = DiscordBot::getInstance()->request->send($url);

        foreach ($channels as $id => $channel) {
            if ($channel->type !== 0) {
                unset($channels[$id]);
            }
        }
        
        usort($channels, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return array_values($channels);
    }

    public function emojis($guildId)
    {
        $url = 'guilds/' . $guildId . '/emojis';
        
        $emojis = DiscordBot::getInstance()->request->send($url);

        usort($emojis, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return $emojis;
    }

    public function roles($guildId)
    {
        $url = 'guilds/' . $guildId . '/roles';

        $roles = DiscordBot::getInstance()->request->send($url);

        usort($roles, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return $roles;
    }
}