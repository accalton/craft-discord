<?php

namespace magi\services;

use craft\elements\Entry;
use magi\Magi;

class MessageService
{
    public function afterEntrySave(Entry $entry)
    {
        switch ($entry->type->handle) {
            case 'roleReaction':
                Magi::getInstance()->reaction->set($entry);
                break;
        }
    }

    public function beforeEntrySave(Entry $entry)
    {
        $color = $entry->color ? hexdec($entry->color->hex) : hexdec('#ffffff');
        $description = $this->description($entry);

        $contents = [
            'body' => json_encode([
                'nonce' => $entry->id,
                'embeds' => [
                    [
                        'title' => $entry->title,
                        'description' => $description,
                        'color' => $color
                    ]
                ]
            ])
        ];

        if ($entry->messageId) {
            $message = $this->updateMessage($entry->channel, $entry->messageId, $contents);
        } else {
            $message = $this->createMessage($entry->channel, $contents);
        }

        $entry->messageId = $message->id;
    }

    public function beforeEntryDelete(Entry $entry)
    {
        if ($entry->messageId) {
            Magi::getInstance()->request->send('DELETE', 'channels/' . $entry->channel . '/messages/' . $entry->messageId);
        }
    }

    private function createMessage(int $channelId, array $contents)
    {
        return Magi::getInstance()->request->send('POST', 'channels/' . $channelId . '/messages', $contents);
    }

    private function description(Entry $entry)
    {
        $description = strip_tags($entry->description);

        switch ($entry->type) {
            case 'roleReaction':
                if ($entry->roleReactions->count()) {
                    $description .= PHP_EOL . PHP_EOL;
                    $guildRoles = Magi::getInstance()->guild->roles($entry->guild);
                    
                    $roles = [];
                    foreach ($guildRoles as $role) {
                        $roles[$role->id] = $role->name;
                    }

                    foreach ($entry->roleReactions->all() as $roleReaction) {
                        $description .= '<:' . $roleReaction->emoji . '>';
                        $description .= ' **' . $roles[$roleReaction->role] . '**';
                        $description .= $roleReaction->description ? ' (' . $roleReaction->description . ')' : '';
                        $description .= PHP_EOL;
                    }
                }
                break;
        }

        return $description;
    }

    private function updateMessage(int $channelId, int $messageId, array $contents)
    {
        return Magi::getInstance()->request->send('PATCH', 'channels/' . $channelId . '/messages/' . $messageId, $contents);
    }
}