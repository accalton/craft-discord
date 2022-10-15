<?php

namespace magi\services;

use Craft;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

class RequestService
{
    private $baseUri = 'https://discordapp.com/api/';

    public function send($method, $url, $params = [])
    {
        $client = Craft::createGuzzleClient([
            'base_uri' => $this->baseUri,
            'headers'  => [
                'Authorization' => 'Bot ' . getenv('DISCORD_TOKEN'),
                'Content-Type'  => 'application/json'
            ]
        ]);

        try {
            $response = $client->request($method, $url, $params);

            $contents = $response->getBody()->getContents();

            return json_decode($contents);
        } catch (ClientException $e) {
            echo $e->getMessage();

            exit;
        }
    }
}