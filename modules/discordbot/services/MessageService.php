<?php

namespace discordbot\services;

use \craft\elements\Entry;
use \discordbot\DiscordBot;

class MessageService
{
    private $webhookId;
    private $webhookToken;

    public function onEntrySave(Entry $entry)
    {
        $this->getWebhook($entry);
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
        $this->getWebhook($entry);
        if ($messageId = $entry->messageId) {
            $url = 'webhooks/' . $this->webhookId . '/' . $this->webhookToken . '/messages/' . $messageId;
            DiscordBot::getInstance()->request->send($url, [], 'DELETE');
        }
    }

    private function getWebhook(Entry $entry)
    {
        $webhook = DiscordBot::getInstance()->webhooks->channel($entry->channel);

        $this->webhookId = $webhook->id;
        $this->webhookToken = $webhook->token;
    }

    private function roleReactionParams(Entry $entry)
    {
        $description = strip_tags($entry->description) . PHP_EOL;

        foreach ($entry->rolesEmojis as $roleEmoji) {
            $description .= PHP_EOL;
            $description .= $roleEmoji->emoji . ' ' . $roleEmoji->role;
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

    private function voteParams(Entry $entry)
    {
        $description = strip_tags($entry->description) . PHP_EOL;

        foreach ($entry->voteOptions as $voteOption) {
            $description .= PHP_EOL;
            $description .= $voteOption->emoji . ' : ' . $voteOption->option;
        }

        $color = $entry->color ? hexdec($entry->color->hex) : hexdec('#ffffff');

        $embed = [
            'title' => $entry->title,
            'description' => $description,
            'color' => $color
        ];

        if ($entry->image->one()) {
            $embed['image'] = [
                'url' => $entry->image->one()->url
            ];
        }

        $params = [
            'body' => json_encode([
                'embeds' => [
                    $embed
                ]
            ])
        ];

        return $params;
    }
}