<?php

namespace penpen\controllers;

use craft\web\Controller;
use penpen\PenPen;

class MemberController extends Controller
{
    public function actionGuilds()
    {
        $this->requireCpRequest();

        $guilds = PenPen::getInstance()->discord->guilds();

        usort($guilds, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        return $this->asJson($guilds);
    }
}
