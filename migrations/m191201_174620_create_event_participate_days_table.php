<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_participate_days}}`.
 */
class m191201_174620_create_event_participate_days_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_participate_days}}', [
            'id' => $this->primaryKey(),
            'event_participate_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
        ]);
        $this->addForeignKey('fk_event_participate_days_event_participate_id', '{{%event_participate_days}}', 'event_participate_id', '{{%event_participate}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event_participate_days}}');
    }
}
