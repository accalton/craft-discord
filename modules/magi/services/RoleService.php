<?php

namespace magi\services;

use craft\elements\Entry;
use Discord\Parts\WebSockets\MessageReaction;

class RoleService
{
    public function add(MessageReaction $reaction)
    {
        $entry = Entry::find()
            ->messageId($reaction->message_id)
            ->one();

        if ($entry) {
            foreach ($entry->roleReactions->all() as $roleReaction) {
                if ($reaction->emoji->id && strpos($roleReaction->emoji, $reaction->emoji->id) !== false) {
                    $reaction->member->addRole($roleReaction->role);
                }
            }
        }
    }

    public function remove(MessageReaction $reaction)
    {
        $entry = Entry::find()
            ->messageId($reaction->message_id)
            ->one();

        if ($entry) {
            foreach ($entry->roleReactions->all() as $roleReaction) {
                if ($reaction->emoji->id && strpos($roleReaction->emoji, $reaction->emoji->id) !== false) {
                    $reaction->member->removeRole($roleReaction->role);
                }
            }
        }
    }
}