<?php

namespace magi\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Cp;
use magi\Magi;

class ChannelField extends Field
{
    public $dropdownType;

    public static function displayName(): string
    {
        return Craft::t('app', 'Discord Channel');
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        $options = ['' => '---'];

        if ($guildId = $element->guild ?? null) {
            $channels = Magi::getInstance()->guild->textChannels($guildId);

            foreach ($channels as $channel) {
                $options[$channel->id] = $channel->name;
            }
        }

        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $value,
            'options' => $options,
            'inputAttributes' => [
                'data-field-type' => 'discord-channels'
            ]
        ]);
    }
}