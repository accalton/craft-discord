<?php

namespace magi\services;

use Craft;
use craft\elements\Entry;
use Discord\Parts\Channel\Message;
use Discord\Parts\WebSockets\MessageReaction;
use magi\Magi;

class ReactionService
{
    public function set(Message $message)
    {
        $entry = Entry::find()
            ->messageId($message->id)
            ->one();

        if (!$entry) {
            $entry = Entry::find()
                ->id($message->nonce)
                ->one();
        }

        if (!$entry) {
            $entry = Entry::find()
                ->id($message->nonce)
                ->drafts()
                ->one();
        }

        if ($entry) {
            $guildEmojis = Magi::getInstance()->guild->emojis($entry->guild);
            
            $emojis = [];
            switch ($entry->type->handle) {
                case 'roleReaction':
                    foreach ($entry->roleReactions->all() as $roleReaction) {
                        $emojis[] = $roleReaction->emoji;
                    }
                    break;
                case 'riotNight':
                    foreach ($entry->vote->all() as $vote) {
                        $emojis[] = $vote->emoji;
                    }
                    break;
            }

            foreach ($guildEmojis as $guildEmoji) {
                $emoji = $guildEmoji->name . ':' . $guildEmoji->id;
                if (in_array($emoji, $emojis)) {
                    $message->react($emoji);
                } else {
                    $message->deleteReaction(Message::REACT_DELETE_ME, $emoji);
                }
            }
        } else {
            var_dump($message->nonce);
            var_dump($entry);exit;
        }
    }

    public function validate(MessageReaction $reaction)
    {
        $entry = Entry::find()
            ->messageId($reaction->message_id)
            ->one();

        if ($entry) {
            $valid = false;
            switch ($entry->type->handle) {
                case 'riotNight':
                    foreach ($entry->vote->all() as $vote) {
                        if ($reaction->emoji->id && strpos($vote->emoji, $reaction->emoji->id) !== false) {
                            $valid = true;
                        }
                    }
                    break;
                case 'roleReaction':
                    foreach ($entry->roleReactions->all() as $roleReaction) {
                        if ($reaction->emoji->id && strpos($roleReaction->emoji, $reaction->emoji->id) !== false) {
                            $valid = true;
                        }
                    }
                    break;
            }

            if (!$valid) {
                $reaction->delete();
            }
        }
    }
}