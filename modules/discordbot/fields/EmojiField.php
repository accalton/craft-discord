<?php

namespace discordbot\fields;

use Craft;
use craft\base\ElementInterface;

use discordbot\DiscordBot;

class EmojiField extends \craft\base\Field
{
    public static function displayName(): string
    {
        return Craft::t('app', 'Emoji');
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        $emojis = DiscordBot::getInstance()->guild->emojis();

        $options = [];
        foreach ($emojis as $id => $name) {
            $options['<:' . $name . ':' . $id . '>'] = $name;
        }

        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $value,
            'options' => $options
        ]);
    }
}