<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m191130_231402_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'start' => $this->datetime()->notNull(),
            'end' => $this->datetime(),
            'description' => $this->text(),
            'place' => $this->string()->notNull(),
            'category' => $this->string()->notNull(),
            'organizer_user_id' => $this->integer(),
            'memo' => $this->text(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addForeignKey('fk_event_created_by', '{{%event}}', 'created_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_event_updated_by', '{{%event}}', 'updated_by', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_event_organizer_user_id', '{{%event}}', 'organizer_user_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }
}
