<?php

namespace magi\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Cp;
use magi\Magi;
use magi\fields\conditions\GuildFieldConditionRule;

class GuildField extends Field
{
    public $dropdownType;

    public static function displayName(): string
    {
        return Craft::t('app', 'Discord Guild');
    }

    protected function inputHtml(mixed $value, ?ElementInterface $element = null): string
    {
        $options = ['' => '---'];
        $guilds = Magi::getInstance()->member->guilds();
        foreach ($guilds as $guild) {
            $options[$guild->id] = $guild->name;
        }

        return Craft::$app->getView()->renderTemplate('_includes/forms/select', [
            'id' => $this->getInputId(),
            'describedBy' => $this->describedBy,
            'name' => $this->handle,
            'value' => $value,
            'options' => $options,
        ]);
    }

    public function getElementConditionRuleType(): ?string
    {
        return GuildFieldConditionRule::class;
    }
}