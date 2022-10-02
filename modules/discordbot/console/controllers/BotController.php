<?php

namespace discordbot\console\controllers;

use craft\console\Controller;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Websockets\MessageReaction;
use Discord\Websockets\Event;
use Discord\Websockets\Intents;
use discordbot\DiscordBot;

class BotController extends Controller
{
    protected $discord;

    public function actionIndex()
    {
        $this->discord = new Discord([
            'token' => getenv('DISCORD_TOKEN'),
            'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS | Intents::MESSAGE_CONTENT,
            'loadAllMembers' => true
        ]);

        $this->discord->on('ready', function() {
            $this->discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
                if ($this->isBotMessage($message, $discord)) {
                    DiscordBot::getInstance()->reactions->set($message);
                }
            });

            $this->discord->on(Event::MESSAGE_UPDATE, function (Message $message, Discord $discord) {
                if ($this->isBotMessage($message, $discord)) {
                    DiscordBot::getInstance()->reactions->set($message);
                }
            });

            $this->discord->on(Event::MESSAGE_REACTION_ADD, function(MessageReaction $reaction, Discord $discord) {
                if ($reaction->user_id !== $discord->user->id) {
                    DiscordBot::getInstance()->roles->set($reaction, 'add');
                }
            });

            $this->discord->on(Event::MESSAGE_REACTION_REMOVE, function(MessageReaction $reaction, Discord $discord) {
                if ($reaction->user_id !== $discord->user->id) {
                    DiscordBot::getInstance()->roles->set($reaction, 'remove');
                }
            });

        });

        $this->discord->run();
    }

    private function isBotMessage(Message $message, Discord $discord)
    {
        return ($message->author->bot && $message->application_id == $discord->user->id);
    }
}