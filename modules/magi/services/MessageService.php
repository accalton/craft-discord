<?php

namespace magi\services;

use craft\elements\Entry;
use magi\Magi;

class MessageService
{
    public function beforeEntrySave(Entry $entry)
    {
        $color = $entry->color ? hexdec($entry->color->hex) : hexdec('#ffffff');
        $description = $this->description($entry);

        $body = [
            'nonce' => $entry->id,
            'embeds' => [
                [
                    'title' => $entry->title,
                    'description' => $description,
                    'color' => $color
                ]
            ]
        ];

        if ($entry->type->handle === 'riotNight') {
            $body['content'] = $this->addMentions($entry);
        }

        $contents = [
            'body' => json_encode($body)
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

    private function addMentions(Entry $entry)
    {
        $roleMentions = [];
        foreach ($entry->roleMentions->all() as $roleMention) {
            if ($roleMention->enabled) {
                $roleMentions[] = '<@&' . $roleMention->role . '>';
            }
        }

        $content = '';
        if ($roleMentions) {
            $content .= implode(', ', $roleMentions);
        }

        return $content;
    }

    private function createMessage(int $channelId, array $contents)
    {
        return Magi::getInstance()->request->send('POST', 'channels/' . $channelId . '/messages', $contents);
    }

    private function description(Entry $entry)
    {
        switch ($entry->type) {
            case 'riotNight':
                $description = $this->riotNight($entry);
                break;
            case 'roleReaction':
                $description = $this->roleReaction($entry);
                break;
        }

        return $description;
    }

    private function riotNight(Entry $entry)
    {
        $description = strip_tags($entry->description);

        if ($entry->vote->count()) {
            $description .= PHP_EOL . PHP_EOL;

            foreach ($entry->vote->all() as $vote) {
                $description .= '<:' . $vote->emoji . '>';
                $description .= ' **' . $vote->description . '**' . PHP_EOL;
            }
        }

        return $description;
    }

    private function roleReaction(Entry $entry)
    {
        $description = strip_tags($entry->description);

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

        return $description;
    }

    private function updateMessage(int $channelId, int $messageId, array $contents)
    {
        return Magi::getInstance()->request->send('PATCH', 'channels/' . $channelId . '/messages/' . $messageId, $contents);
    }
}