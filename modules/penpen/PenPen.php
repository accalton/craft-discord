<?php

namespace penpen;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\TemplateEvent;
use craft\web\View;
use craft\web\twig\variables\Cp;
use yii\base\Event;
use yii\base\Module;

class PenPen extends Module
{
    public function init()
    {
        Craft::setAlias('@penpen', __DIR__);

        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'penpen\\console\\controllers';
        } else {
            $this->controllerNamespace = 'penpen\\controllers';
        }

        $this->setComponents([
            'discord' => \penpen\services\DiscordService::class
        ]);

        $this->registerEvents();

        parent::init();
    }

    private function registerEvents()
    {
        Event::on(
            View::class,
            View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
            function (RegisterTemplateRootsEvent $event) {
                $event->roots[$this->id] = __DIR__ . '/templates';
            }
        );

        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function (RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'url' => 'penpen',
                    'label' => 'PenPen'
                ];
            }
        );

        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function (TemplateEvent $event) {
                if (Craft::$app->getRequest()->isCpRequest) {
                    Craft::$app->getView()->registerAssetBundle(PenPenBundle::class);
                }
            }
        );
    }
}