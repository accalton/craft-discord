<?php

namespace discordbot\services;

use Craft;
use craft\elements\Entry;

class RoleReactService
{
    public function foobar()
    {
        $webhookId = '1025225336683765812';
        $webhookToken = '-q2grf0ztbqhEbcuVrjRz1RucINa4HAiJbwpHz1zolFOR3Pj3qHtHqI31D7Dknmajhhh';
        $url = 'https://discordapp.com/api/webhooks/' . $webhookId . '/' . $webhookToken;

        $client = Craft::createGuzzleClient();

        try {
            $response = $client->request('POST', $url, [
                'body' => json_encode([
                    'content' => 'This is a test',
                    'username' => 'Riot Nights'
                ]),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $contents = $response->getBody()->getContents();
            var_dump(json_decode($contents));
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        exit;
    }

    public function createWebhook()
    {
        $url = 'https://discordapp.com/api/channels/991883337977307226/webhooks';

        $client = Craft::createGuzzleClient();

        try {
            $response = $client->request('POST', $url, [
                'body' => json_encode([
                    'name' => 'Testing this out'
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