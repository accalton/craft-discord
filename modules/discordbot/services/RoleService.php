<?php

namespace discordbot\services;

use craft\elements\Entry;
use Discord\Parts\WebSockets\MessageReaction;
use discordbot\DiscordBot;

class RoleService
{
    public function set(MessageReaction $reaction, $action)
    {
        $entry = Entry::find()
            ->messageId($reaction->message_id)
            ->type('roleReaction')
            ->one();

        if ($entry) {
            foreach ($entry->rolesEmojis as $roleEmoji) {
                if ($reaction->emoji->id && strpos($roleEmoji->emoji, $reaction->emoji->id) !== false) {
                    $role = $this->getRole($roleEmoji->role);
                    switch ($action) {
                        case 'add':
                            $reaction->member->addRole($role);
                            break;
                        case 'remove':
                            $reaction->member->removeRole($role);
                            break;
                    }
                }
            }
        }
    }

    private function getRole($role)
    {
        $roles = DiscordBot::getInstance()->guild->roles();
        return array_search($role, $roles);
    }
}