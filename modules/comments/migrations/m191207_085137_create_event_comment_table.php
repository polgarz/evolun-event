<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_comment}}`.
 */
class m191207_085137_create_event_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_comment}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'event_id' => $this->integer()->notNull(),
            'comment' => $this->text(),
            'date' => $this->datetime(),
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addForeignKey('fk_event_comment_user_id', '{{%event_comment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_event_comment_event_id', '{{%event_comment}}', 'event_id', '{{%event}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event_comment}}');
    }
}
