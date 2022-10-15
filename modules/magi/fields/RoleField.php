<?php

namespace magi\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Cp;
use magi\Magi;

class RoleField extends Field
{
    public $dropdownType;

    public static function displayName(): string
    {
        return Craft::t('app', 'Discord Role');
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        $options = ['' => '---'];

        if ($guildId = $element->owner->guild ?? null) {
            $roles = Magi::getInstance()->guild->roles($guildId);

            foreach ($roles as $role) {
                $options[$role->id] = $role->name;
            }
        }

        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $value,
            'options' => $options,
            'inputAttributes' => [
                'data-field-type' => 'discord-roles'
            ]
        ]);
    }
}