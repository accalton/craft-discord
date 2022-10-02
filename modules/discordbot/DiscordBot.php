<?php

namespace discordbot;

use Craft;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\helpers\ElementHelper;
use craft\services\Fields;
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
            'messages'  => \discordbot\services\MessageService::class,
            'reactions' => \discordbot\services\ReactionService::class,
            'request'   => \discordbot\services\RequestService::class,
            'roles'     => \discordbot\services\RoleService::class,
            'webhooks'  => \discordbot\services\WebhookService::class,
        ]);

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = \discordbot\fields\DiscordDropdownField::class;
            }
        );

        Event::on(
            Entry::class,
            Entry::EVENT_BEFORE_SAVE,
            function (ModelEvent $event) {
                $entry = $event->sender;

                if (ElementHelper::isDraftOrRevision($entry)) {
                    return;
                }

                switch ($entry->section->handle) {
                    case 'messages':
                        $this->messages->onEntrySave($entry);
                        break;
                }
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

                switch ($entry->section->handle) {
                    case 'messages':
                        $this->messages->onEntryDelete($entry);
                        break;
                }
            }
        );
    }
}