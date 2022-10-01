<?php

namespace discordbot\services;

use \craft\elements\Entry;
use \discordbot\DiscordBot;

class MessageService
{
    private $webhookId    = '1025225336683765812';
    private $webhookToken = '-q2grf0ztbqhEbcuVrjRz1RucINa4HAiJbwpHz1zolFOR3Pj3qHtHqI31D7Dknmajhhh';

    public function onEntrySave(Entry $entry)
    {
        $url = 'webhooks/' . $this->webhookId . '/' . $this->webhookToken;
        if ($entry->messageId) {
            $url .= '/messages/' . $entry->messageId;
        }

        if (method_exists($this, $entry->type->handle . 'Params')) {
            $setParams = $entry->type->handle . 'Params';
            $params = $this->$setParams($entry);

            if ($response = DiscordBot::getInstance()->request->send($url, $params, $entry->messageId ? 'PATCH' : 'POST')) {
                $entry->messageId = $response->id;
            }
        }
    }

    public function onEntryDelete(Entry $entry)
    {
        if ($messageId = $entry->messageId) {
            $url = 'webhooks/' . $this->webhookId . '/' . $this->webhookToken . '/messages/' . $messageId;
            DiscordBot::getInstance()->request->send($url, [], 'DELETE');
        }
    }

    private function roleReactionParams(Entry $entry)
    {
        $description = strip_tags($entry->description) . PHP_EOL;

        foreach ($entry->rolesEmojis as $roleEmoji) {
            $description .= PHP_EOL;
            $description .= $roleEmoji->emoji . ' : ' . $roleEmoji->role;
        }

        $color = $entry->color ? hexdec($entry->color->hex) : hexdec('#ffffff');

        $params = [
            'body' => json_encode([
                'embeds' => [
                    [
                        'title' => $entry->title,
                        'description' => $description,
                        'color' => $color
                    ]
                ]
            ])
        ];

        return $params;
    }
}