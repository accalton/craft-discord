<?php

namespace imageoptimization;

use Craft;
use imageoptimization\twigextensions\TwigExtension;

/**
 * 
 */
class ImageOptimization extends \yii\base\Module
{
    public function init()
    {
        Craft::setAlias('@imageoptimization', __DIR__);

        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'imageoptimization\\console\\controllers';
        } else {
            $this->controllerNamespace = 'imageoptimization\\controllers';
        }

        parent::init();

        if (Craft::$app->getRequest()->getIsSiteRequest()) {
            Craft::$app->view->registerTwigExtension(new TwigExtension());
        }
    }
}