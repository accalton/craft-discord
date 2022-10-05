<?php

namespace discordbot;

use Craft;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\TemplateEvent;
use craft\helpers\ElementHelper;
use craft\services\Fields;
use craft\web\twig\variables\Cp;
use craft\web\View;
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
            'member'    => \discordbot\services\MemberService::class,
            'messages'  => \discordbot\services\MessageService::class,
            'reactions' => \discordbot\services\ReactionService::class,
            'request'   => \discordbot\services\RequestService::class,
            'roles'     => \discordbot\services\RoleService::class,
            'webhooks'  => \discordbot\services\WebhookService::class,
        ]);

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
                    'url' => 'discordbot',
                    'label' => 'Discord'
                ];
            }
        );

        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function (TemplateEvent $event) {
                if (Craft::$app->getRequest()->isCpRequest) {
                    Craft::$app->getView()->registerAssetBundle(DiscordBotBundle::class);
                }
            }
        );

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