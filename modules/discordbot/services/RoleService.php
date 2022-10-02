<?php

namespace discordbot\services;

use craft\elements\Entry;
use discordbot\DiscordBot;

class RoleService
{
    public function set($reaction, $action)
    {
        $entry = Entry::find()
            ->messageId($reaction->message_id)
            ->one();

        $valid = false;
        if ($entry) {
            foreach ($entry->rolesEmojis as $roleEmoji) {
                if ($reaction->emoji->id && strpos($roleEmoji->emoji, $reaction->emoji->id) !== false) {
                    $valid = true;
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

        if (!$valid) {
            $reaction->delete();
        }
    }

    private function getRole($role)
    {
        $roles = DiscordBot::getInstance()->guild->roles();
        return array_search($role, $roles);
    }
}