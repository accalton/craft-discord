<?php

namespace discordbot\controllers;

use Craft;
use craft\web\Controller;
use discordbot\DiscordBot;

class GuildController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionPost()
    {
        var_dump($_POST);

        exit;
    }

    public function actionChannels($guildId)
    {
        $this->requireCpRequest();
        
        $channels = DiscordBot::getInstance()->guild->channels($guildId);

        return $this->asJson($channels);
    }

    public function actionEmojis($guildId)
    {
        $this->requireCpRequest();

        $emojis = DiscordBot::getInstance()->guild->emojis($guildId);

        return $this->asJson($emojis);
    }

    public function actionRoles($guildId)
    {
        $this->requireCpRequest();

        $roles = DiscordBot::getInstance()->guild->roles($guildId);

        return $this->asJson($roles);
    }
}