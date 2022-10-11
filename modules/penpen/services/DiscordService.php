<?php

namespace penpen\services;

use Craft;
use GuzzleHttp\Exception\ClientException;

class DiscordService
{
    private $config = [];

    public function __construct()
    {
        $this->config = [
            'base_uri' => 'https://discordapp.com/api/',
            'headers'  => [
                'Authorization' => 'Bot ' . getenv('DISCORD_TOKEN'),
                'Content-Type' => 'application/json'
            ],
            'query' => [
                'wait' => true
            ]
        ];
    }

    public function channels($guildId)
    {
        $client = Craft::createGuzzleClient($this->config);

        try {
            $response = $client->request('GET', 'guilds/' . $guildId . '/channels');
            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            
        }
    }

    public function guilds()
    {
        $client = Craft::createGuzzleClient($this->config);

        try {
            $response = $client->request('GET', 'users/@me/guilds', []);
            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            
        }
    }
}
