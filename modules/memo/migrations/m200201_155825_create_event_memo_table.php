<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_memo}}`.
 */
class m200201_155825_create_event_memo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_memo}}', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer(),
            'content' => $this->text(),
            'created_at' => $this->datetime(),
            'created_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk_event_memo_created_by', '{{%event_memo}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_event_memo_event_id', '{{%event_memo}}', 'event_id', '{{%event}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event_memo}}');
    }
}
