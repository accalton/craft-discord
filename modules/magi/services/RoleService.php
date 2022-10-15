<?php

namespace magi\services;

use craft\elements\Entry;
use Discord\Parts\WebSockets\MessageReaction;
use magi\Magi;

class RoleService
{
    public function add(MessageReaction $reaction)
    {
        $entry = Entry::find()
            ->messageId($reaction->message_id)
            ->one();

        if ($entry) {
            switch ($entry->type->handle) {
                case 'riotNight':
                    if ($entry->riotNightRoleId) {
                        $reaction->member->addRole($entry->riotNightRoleId);
                    }
                    break;
                case 'roleReaction':
                    foreach ($entry->roleReactions->all() as $roleReaction) {
                        if ($reaction->emoji->id && strpos($roleReaction->emoji, $reaction->emoji->id) !== false) {
                            $reaction->member->addRole($roleReaction->role);
                        }
                    }
                    break;
            }
        }
    }

    public function createRiotNight(Entry $entry)
    {
        $color = $entry->color ? hexdec($entry->color->hex) : hexdec('#ffffff');
        $params = [
            'body' => json_encode([
                'name'  => 'Riot Night (' . $entry->date->format('M jS, Y @ g:ia') . ')',
                'color' => $color,
                'hoist' => true,
                'mentionable' => true
            ])
        ];

        $role = Magi::getInstance()->request->send('POST', 'guilds/' . $entry->guild . '/roles', $params);

        $entry->riotNightRoleId = $role->id;
    }

    public function deleteRiotNight(Entry $entry)
    {
        return Magi::getInstance()->request->send('DELETE', 'guilds/' . $entry->guild . '/roles/' . $entry->riotNightRoleId);
    }

    public function remove(MessageReaction $reaction)
    {
        $entry = Entry::find()
            ->messageId($reaction->message_id)
            ->one();

        if ($entry) {
            switch ($entry->type->handle) {
                case 'roleReaction':
                    foreach ($entry->roleReactions->all() as $roleReaction) {
                        if ($reaction->emoji->id && strpos($roleReaction->emoji, $reaction->emoji->id) !== false) {
                            $reaction->member->removeRole($roleReaction->role);
                        }
                    }
                    break;
            }
        }
    }
}