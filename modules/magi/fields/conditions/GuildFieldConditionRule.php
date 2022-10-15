<?php

namespace magi\fields\conditions;

use craft\base\conditions\BaseConditionRule;
use craft\fields\conditions\FieldConditionRuleInterface;
use craft\fields\conditions\FieldConditionRuleTrait;

class GuildFieldConditionRule extends BaseConditionRule implements FieldConditionRuleInterface
{
    use FieldConditionRuleTrait;

    /**
     * @inheritdoc
     */
    public string $operator = self::OPERATOR_NOT_EMPTY;

    /**
     * @inheritdoc
     */
    public static function supportsProjectConfig(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function operators(): array
    {
        return array_filter([
            self::OPERATOR_NOT_EMPTY,
            self::OPERATOR_EMPTY,
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function elementQueryParam(): int|string|null
    {
        return match ($this->operator) {
            self::OPERATOR_EMPTY => ':empty:',
            self::OPERATOR_NOT_EMPTY => 'not :empty:',
            default => throw new InvalidConfigException("Invalid operator: $this->operator"),
        };
    }

    /**
     * @inheritdoc
     */
    protected function matchFieldValue($value): bool
    {
        return !empty($value);
    }
}