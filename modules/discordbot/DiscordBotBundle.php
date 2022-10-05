<?php

namespace discordbot;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class DiscordBotBundle extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = '@discordbot/resources/dist';

        $this->depends = [
            CpAsset::class
        ];

        $this->js = [
            'app.js'
        ];

        $this->css = [];

        parent::init();
    }
}