<?php

namespace discordbot;

use Craft;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\helpers\ElementHelper;
use yii\base\Event;
use yii\base\Module;

class DiscordBot extends Module
{
    public function init()
    {
        Craft::setAlias('@discordbot', __DIR__);

        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'discordbot\\console\\controllers';
        } else {
            $this->controllerNamespace = 'discordbot\\controllers';
        }

        parent::init();

        $this->setComponents([
            'guild'     => \discordbot\services\GuildService::class,
            'request'   => \discordbot\services\RequestService::class,
            'roleReact' => \discordbot\services\RoleReactService::class,
            'webhooks'  => \discordbot\services\WebhookService::class,
        ]);

        Event::on(
            Entry::class,
            Entry::EVENT_BEFORE_SAVE,
            function (ModelEvent $event) {
                $entry = $event->sender;

                if (ElementHelper::isDraftOrRevision($entry)) {
                    return;
                }

                $this->roleReact->onEntrySave($entry);
            }
        );

        Event::on(
            Entry::class,
            Entry::EVENT_BEFORE_DELETE,
            function (ModelEvent $event) {
                $entry = $event->sender;

                if (ElementHelper::isDraftOrRevision($entry)) {
                    return;
                }

                $this->roleReact->onEntryDelete($entry);
            }
        );
    }
}