<?php

namespace magi\console\controllers;

use craft\console\Controller;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Discord\Parts\WebSockets\MessageReaction;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use magi\Magi;

class MagiController extends Controller
{
    protected $discord;

    public function actionIndex()
    {
        $this->discord = new Discord([
            'token' => getenv('DISCORD_TOKEN'),
            'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS | Intents::MESSAGE_CONTENT | Intents::GUILD_SCHEDULED_EVENTS,
            'loadAllMembers' => true
        ]);

        $this->discord->on('ready', function() {
            $this->discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
                if ($this->isBotMessage($message, $discord)) {
                    Magi::getInstance()->reaction->set($message);
                }
            });

            $this->discord->on(Event::MESSAGE_UPDATE, function (Message $message, Discord $discord) {
                if ($this->isBotMessage($message, $discord)) {
                    Magi::getInstance()->reaction->set($message);
                }
            });

            $this->discord->on(Event::MESSAGE_REACTION_ADD, function(MessageReaction $reaction, Discord $discord) {
                if ($reaction->user_id !== $discord->user->id) {
                    Magi::getInstance()->role->add($reaction);
                    Magi::getInstance()->reaction->validate($reaction);
                }
            });

            $this->discord->on(Event::MESSAGE_REACTION_REMOVE, function(MessageReaction $reaction, Discord $discord) {
                if ($reaction->user_id !== $discord->user->id) {
                    Magi::getInstance()->role->remove($reaction);
                }
            });

            $this->discord->on(Event::GUILD_SCHEDULED_EVENT_USER_ADD, function($data, Discord $discord) {
                $this->discord->guilds->fetch($data->guild_id)->done(function (Guild $guild) use ($data) {
                    $guild->members->fetch($data->user_id)->done(function (Member $member) use ($data) {
                        Magi::getInstance()->role->assignRiotNight($member, $data->guild_scheduled_event_id);
                    });
                });
            });

            $this->discord->on(Event::GUILD_SCHEDULED_EVENT_USER_REMOVE, function($data, Discord $discord) {
                $this->discord->guilds->fetch($data->guild_id)->done(function (Guild $guild) use ($data) {
                    $guild->members->fetch($data->user_id)->done(function (Member $member) use ($data) {
                        Magi::getInstance()->role->removeRiotNight($member, $data->guild_scheduled_event_id);
                    });
                });
            });
        });

        $this->discord->run();
    }

    private function isBotMessage(Message $message, Discord $discord)
    {
        return ($message->author->bot && $message->author->id == $discord->user->id);
    }
}