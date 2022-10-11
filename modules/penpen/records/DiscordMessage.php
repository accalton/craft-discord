<?php

namespace penpen\records;

use craft\db\ActiveRecord;

class DiscordMessage extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%discord_messages}}';
    }
}