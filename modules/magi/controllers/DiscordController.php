<?php

namespace magi\controllers;

use craft\web\Controller;
use magi\Magi;

class DiscordController extends Controller
{
    public function actionChannels($guildId = null)
    {
        $this->requireCpRequest();

        if (!$guildId) {
            return $this->asJson([]);
        }

        $channels = Magi::getInstance()->guild->textChannels($guildId);

        return $this->asJson($channels);
    }

    public function actionEmojis($guildId = null)
    {
        $this->requireCpRequest();

        if (!$guildId) {
            return $this->asJson([]);
        }

        $emojis = Magi::getInstance()->guild->emojis($guildId);

        return $this->asJson($emojis);
    }

    public function actionRoles($guildId = null)
    {
        $this->requireCpRequest();

        if (!$guildId) {
            return $this->asJson([]);
        }

        $roles = Magi::getInstance()->guild->roles($guildId);

        return $this->asJson($roles);
    }
}
