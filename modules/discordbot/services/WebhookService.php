<?php

namespace discordbot\services;

class WebhookService
{
    public function createWebhook()
    {
        $url = 'https://discordapp.com/api/channels/991883337977307226/webhooks';

        $client = Craft::createGuzzleClient();

        try {
            $response = $client->request('POST', $url, [
                'body' => json_encode([
                    'name' => 'Role Assignments'
                ]),
                'headers' => [
                    'Authorization' => 'Bot ' . getenv('DISCORD_TOKEN'),
                    'Content-Type'  => 'application/json'
                ]
            ]);

            $contents = $response->getBody()->getContents();
            var_dump(json_decode($contents));
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        exit;
    }
}