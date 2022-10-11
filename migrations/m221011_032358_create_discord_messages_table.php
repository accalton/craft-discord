<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m221011_032358_create_message_records_table migration.
 */
class m221011_032358_create_discord_messages_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->createTable('{{%discord_messages}}', [
            'messageId' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'PRIMARY KEY(messageId)'
        ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        if ($this->db->tableExists('{{%discord_messages}}')) {
            $this->dropTable('{{%discord_messages}}');
        }

        return true;
    }
}
