<?php

namespace discordbot\fields;

use Craft;
use craft\base\ElementInterface;
use craft\helpers\Cp;
use discordbot\DiscordBot;

class DiscordDropdownField extends \craft\base\Field
{
    public $dropdownType;

    public static function displayName(): string
    {
        return Craft::t('app', 'Discord Dropdown');
    }

    public function getSettingsHtml(): ?string
    {
        $options = [
            '' => '---',
            'emojis' => 'Emojis',
            'roles'  => 'Roles'
        ];

        return Cp::selectFieldHtml([
            'fieldClass' => null,
            'label' => Craft::t('app', 'Select an option'),
            'id' => 'dropdown-type',
            'name' => 'dropdownType',
            'options' => $options,
            'value' => $this->dropdownType
        ]);
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        switch ($this->dropdownType) {
            case 'emojis':
                $options = $this->getEmojis();
                break;
            case 'roles':
                $options = $this->getRoles();
                break;
            default:
                $options = [];
        }

        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $value,
            'options' => $options
        ]);
    }

    private function getEmojis()
    {
        $emojis = DiscordBot::getInstance()->guild->emojis();

        $options = [];
        foreach ($emojis as $id => $name) {
            $options['<:' . $name . ':' . $id . '>'] = $name;
        }

        return $options;
    }

    private function getRoles()
    {
        $roles = DiscordBot::getInstance()->guild->roles();

        $options = [];
        foreach ($roles as $id => $role) {
            $options[$role] = $role;
        }

        return $options;
    }
}