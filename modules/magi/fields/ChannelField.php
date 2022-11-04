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
    public $channelType;

    public static function displayName(): string
    {
        return Craft::t('app', 'Discord Channel');
    }

    public function getSettingsHtml(): ?string
    {
        $options = [
            '' => '---',
            'text' => 'Text',
            'voice' => 'Voice'
        ];

        return Cp::selectFieldHtml([
            'fieldClass' => null,
            'label' => Craft::t('app', 'Select an option'),
            'id' => 'channel-type',
            'name' => 'channelType',
            'options' => $options,
            'value' => $this->channelType
        ]);
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        $options = ['' => '---'];

        if ($guildId = $element->guild ?? null) {
            switch ($this->channelType) {
                case 'text':
                    $channels = Magi::getInstance()->guild->textChannels($guildId);
                    break;
                case 'voice':
                    $channels = Magi::getInstance()->guild->voiceChannels($guildId);
                    break;
            }

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
                'data-field-type' => 'discord-channels-' . $this->channelType
            ]
        ]);
    }
}