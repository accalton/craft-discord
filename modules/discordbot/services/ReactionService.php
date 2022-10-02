<?php

namespace discordbot\services;

use craft\elements\Entry;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\WebSockets\MessageReaction;
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

    public function validate(MessageReaction $reaction)
    {
        $entry = Entry::find()
            ->messageId($reaction->message_id)
            ->one();

        if ($entry) {
            switch ($entry->type) {
                case 'roleReaction':
                    $blocks = $entry->rolesEmojis;
                    break;
                case 'vote':
                    $blocks = $entry->voteOptions;
                    break;
            }

            $valid = false;
            foreach ($blocks as $block) {
                if ($reaction->emoji->id && strpos($block->emoji, $reaction->emoji->id) !== false) {
                    $valid = true;
                }
            }

            if (!$valid) {
                $reaction->delete();
            }
        }
    }
}