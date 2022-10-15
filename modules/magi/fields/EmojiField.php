<?php

namespace magi\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Cp;
use magi\Magi;

class EmojiField extends Field
{
    public $dropdownType;

    public static function displayName(): string
    {
        return Craft::t('app', 'Discord Emoji');
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        $options = ['' => '---'];

        if ($guildId = $element->owner->guild ?? null) {
            $emojis = Magi::getInstance()->guild->emojis($guildId);

            foreach ($emojis as $emoji) {
                $options[$emoji->name . ':' . $emoji->id] = $emoji->name;
            }
        }

        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $value,
            'options' => $options,
            'inputAttributes' => [
                'data-field-type' => 'discord-emojis'
            ]
        ]);
    }
}