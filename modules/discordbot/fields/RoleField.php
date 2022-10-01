<?php

namespace discordbot\fields;

use Craft;
use craft\base\ElementInterface;

use discordbot\DiscordBot;

class RoleField extends \craft\base\Field
{
    public static function displayName(): string
    {
        return Craft::t('app', 'Role');
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        $roles = DiscordBot::getInstance()->guild->roles();

        $options = [];
        foreach ($roles as $id => $role) {
            $options[$role] = $role;
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