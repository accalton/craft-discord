<?php

namespace discordbot\controllers;

use craft\web\Controller;
use discordbot\DiscordBot;

class GuildController extends Controller
{
    public function actionChannels($guildId)
    {
        $this->requireCpRequest();
        
        $channels = DiscordBot::getInstance()->guild->channels();

        return $this->asJson($channels);
    }
}