<?php

namespace magi;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class MagiBundle extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = '@magi/resources/dist';

        $this->depends = [
            CpAsset::class
        ];

        $this->js = [
            'main.js'
        ];

        $this->css = [];

        parent::init();
    }
}