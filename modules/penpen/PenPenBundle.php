<?php

namespace penpen;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class PenPenBundle extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = '@penpen/resources/dist';

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
