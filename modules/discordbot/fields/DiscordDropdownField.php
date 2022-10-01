<?php

namespace discordbot\fields;

class DiscordDropdownField extends \craft\base\Field
{
    public static function displayName(): string
    {
        return Craft::t('app', 'Discord Dropdown');
    }

    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'options' => [
                'emojis' => 'Emojis',
                'roles'  => 'Roles'
            ]
        ]);
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $value,
            'options' => []
        ]);
    }
}