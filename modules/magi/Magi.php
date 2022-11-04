<?php

namespace magi;

use Craft;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\TemplateEvent;
use craft\helpers\ElementHelper;
use craft\services\Fields;
use craft\web\View;
use yii\base\Event;
use yii\base\Module;

class Magi extends Module
{
    public function init()
    {
        Craft::setAlias('@magi', __DIR__);

        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'magi\\console\\controllers';
        } else {
            $this->controllerNamespace = 'magi\\controllers';
        }

        parent::init();

        $this->setComponents([
            'event'    => \magi\services\EventService::class,
            'guild'    => \magi\services\GuildService::class,
            'member'   => \magi\services\MemberService::class,
            'messages' => \magi\services\MessageService::class,
            'reaction' => \magi\services\ReactionService::class,
            'request'  => \magi\services\RequestService::class,
            'role'     => \magi\services\RoleService::class,
        ]);

        $this->registerEvents();
    }

    private function registerEvents()
    {
        Event::on(
            View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function (TemplateEvent $event) {
                if (Craft::$app->getRequest()->isCpRequest) {
                    Craft::$app->getView()->registerAssetBundle(MagiBundle::class);
                }
            }
        );

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = \magi\fields\ChannelField::class;
                $event->types[] = \magi\fields\EmojiField::class;
                $event->types[] = \magi\fields\GuildField::class;
                $event->types[] = \magi\fields\RoleField::class;
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

                $this->messages->beforeEntrySave($entry);

                if ($entry->type->handle === 'riotNight') {
                    $this->event->beforeEntrySave($entry);

                    if (!$entry->riotNightRoleId) {
                        $this->role->createRiotNight($entry);
                    }
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

                if ($entry->messageId) {
                    $this->messages->beforeEntryDelete($entry);
                }

                if ($entry->riotNightRoleId) {
                    $this->role->deleteRiotNight($entry);
                }

                if ($entry->scheduledEventId) {
                    $this->event->beforeEntryDelete($entry);
                }
            }
        );
    }
}