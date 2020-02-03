<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_participate}}`.
 */
class m191201_174552_create_event_participate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_participate}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'event_id' => $this->integer()->notNull(),
            'role' => $this->string(50),
        ]);

        $this->addForeignKey('fk_event_participate_user_id', '{{%event_participate}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_event_participate_event_id', '{{%event_participate}}', 'event_id', '{{%event}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event_participate}}');
    }
}
