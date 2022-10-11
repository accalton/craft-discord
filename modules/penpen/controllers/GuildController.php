<?php

namespace penpen\controllers;

use Craft;
use GuzzleHttp\Exception\ClientException;
use penpen\PenPen;

class GuildController extends \craft\web\controller
{
    public function actionChannels($guildId)
    {
        $this->requireCpRequest();

        $channels = PenPen::getInstance()->discord->channels($guildId);

        usort($channels, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        foreach ($channels as $id => $channel) {
            if ($channel->type !== 0) {
                unset($channels[$id]);
            }
        }

        return $this->asJson(array_values($channels));
    }
}