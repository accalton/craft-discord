<?php

namespace discordbot\console\controllers;

use craft\console\Controller;
use Discord\Discord;
use Discord\Websockets\Intents;

class BotController extends Controller
{
    protected $discord;

    public function actionIndex()
    {
        $this->discord = new Discord([
            'token' => getenv('DISCORD_TOKEN'),
            'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS
        ]);

        $this->discord->on('ready', function() {
            //
        });

        $this->discord->run();
    }
}