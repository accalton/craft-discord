<?php

namespace discordbot\controllers;

use craft\web\Controller;
use discordbot\DiscordBot;

class MemberController extends Controller
{
    public function actionGuilds()
    {
        $this->requireCpRequest();

        $guilds = DiscordBot::getInstance()->member->guilds();

        return $this->asJson($guilds);
    }
}