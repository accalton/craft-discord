<?php

namespace discordbot\services;

use Craft;
use craft\elements\Entry;
use discordbot\DiscordBot;

class RoleReactService
{
    private $webhookId    = '1025225336683765812';
    private $webhookToken = '-q2grf0ztbqhEbcuVrjRz1RucINa4HAiJbwpHz1zolFOR3Pj3qHtHqI31D7Dknmajhhh';
    private $url;

    public function __construct()
    {
        $this->url = 'webhooks/' . $this->webhookId . '/' . $this->webhookToken;
    }

    public function onEntryDelete(Entry $entry)
    {
        if ($discordMessageId = $entry->discordMessageId) {
            $url = $this->url . '/messages/' . $discordMessageId;
            DiscordBot::getInstance()->request->processRequest($url, [], 'DELETE');
        }
    }

    public function onEntrySave(Entry $entry)
    {
        // Check if message already exists on the server
        if ($entry->discordMessageId) {
            $this->updateEmbed($entry);
        } else {
            $this->createNewEmbed($entry);
        }
    }

    private function createNewEmbed(Entry $entry)
    {
        $params = $this->setParams($entry);

        if ($response = DiscordBot::getInstance()->request->processRequest($this->url, $params, 'POST')) {
            $entry->discordMessageId = $response->id;
        }
    }

    private function setParams(Entry $entry)
    {
        $description = strip_tags($entry->description) . PHP_EOL;

        $emojis = DiscordBot::getInstance()->guild->emojis();
        $roles = DiscordBot::getInstance()->guild->roles();

        foreach ($entry->rolesEmojis as $roleEmoji) {
            $role = $emoji = false;
            if (array_key_exists($roleEmoji['emoji'], $emojis)) {
                $emoji = '<:' . $roleEmoji['emoji'] . ':' . $emojis[$roleEmoji['emoji']] . '>';
            }

            if (array_key_exists($roleEmoji['role'], $roles)) {
                $role = $roleEmoji['role'];
            }

            if ($emoji && $role) {
                $description .= PHP_EOL;
                $description .= $emoji . ' : ' . $roleEmoji['role'];
            }
        }

        $params = [
            'body' => json_encode([
                'embeds' => [
                    [
                        'title' => $entry->title,
                        'description' => $description
                    ]
                ]
            ])
        ];

        return $params;
    }

    private function updateEmbed(Entry $entry)
    {
        $url = $this->url . '/messages/' . $entry->discordMessageId;
        $params = $this->setParams($entry);

        DiscordBot::getInstance()->request->processRequest($url, $params, 'PATCH');
    }
}