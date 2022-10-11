<?php

namespace discordbot\controllers;

use Craft;
use craft\web\Controller;

class DiscordController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionPost()
    {
        var_dump(Craft::$app->getRequest()->post());

        exit;
    }
}