<?php

namespace discordbot\services;

use discordbot\DiscordBot;

class WebhookService
{
    public function createWebhook($channelId)
    {
        $url = 'channels/' . $channelId . '/webhooks';
        $webhook = DiscordBot::getInstance()->request->send($url, [
            'body' => json_encode([
                'name' => 'Riot Nights'
            ])
        ], 'POST');

        return $webhook;
    }

    public function fetch($channelId)
    {
        $url = 'channels/' . $channelId . '/webhooks';
        
        $webhooks = DiscordBot::getInstance()->request->send($url);

        if (count($webhooks) == 0) {
            return $this->createWebhook($channelId);
        } else {
            return $webhooks[0];
        }
    }
}