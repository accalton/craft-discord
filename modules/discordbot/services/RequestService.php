<?php

namespace discordbot\services;

use Craft;
use GuzzleHttp\Exception\ClientException;

class RequestService
{
    private $baseUri = 'https://discordapp.com/api/';

    public function send($url, $params = [], $method = 'GET')
    {
        $client = Craft::createGuzzleClient([
            'base_uri' => $this->baseUri,
            'headers'  => [
                'Authorization' => 'Bot ' . getenv('DISCORD_TOKEN'),
                'Content-Type' => 'application/json'
            ],
            'query' => [
                'wait' => true
            ]
        ]);

        try {
            $response = $client->request($method, $url, $params);

            $contents = json_decode($response->getBody()->getContents());

            return $contents;
        } catch (ClientException $e) {
            // Log the error
            // var_dump($e->getMessage());
        }

        return false;
    }
}