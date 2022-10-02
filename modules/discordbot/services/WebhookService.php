<?php

namespace discordbot\services;

use discordbot\DiscordBot;

class WebhookService
{
    public function createWebhook($channelId)
    {
        $url = 'channels/' . $channelId . '/webhooks';
        $user = $this->me();

        $imageUrl = 'https://cdn.discordapp.com/avatars/' . $user->id . '/' . $user->avatar . '.png';
        $client = $image = \Craft::createGuzzleClient();
        $response = $client->get($imageUrl);
        $image = $response->getBody()->getContents();

        $webhook = DiscordBot::getInstance()->request->send($url, [
            'body' => json_encode([
                'name'   => $user->username,
                'avatar' => 'data:image/png;base64,' . base64_encode($image)
            ])
        ], 'POST');

        return $webhook;
    }

    public function channel($channelId)
    {
        $url = 'channels/' . $channelId . '/webhooks';
        
        $webhooks = DiscordBot::getInstance()->request->send($url);

        if (count($webhooks) == 0) {
            return $this->createWebhook($channelId);
        } else {
            return $webhooks[0];
        }
    }

    public function id($webhookId)
    {
        $url = 'webhooks/' . $webhookId;

        return DiscordBot::getInstance()->request->send($url);
    }

    private function me()
    {
        $url = 'users/@me';

        return DiscordBot::getInstance()->request->send($url);
    }
}