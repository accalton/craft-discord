<?php

namespace discordbot\services;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use discordbot\DiscordBot;

class ReactionService
{
    public function set(Message $message)
    {
        if ($embed = $message->embeds->first()) {
            $emojis = DiscordBot::getInstance()->guild->emojis();
            foreach ($emojis as $id => $name) {
                $emoji = ':' . $name . ':' . $id;
                if (strpos($embed->description, $emoji) !== false) {
                    $message->react($emoji);
                } else {
                    $message->deleteReaction(Message::REACT_DELETE_ME, $emoji);
                }
            }
        }
    }
}