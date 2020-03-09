<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%kid_absence}}`.
 */
class m200308_104612_create_kid_absence_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%kid_absence}}', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer()->notNull(),
            'kid_id' => $this->integer()->notNull(),
            'reason' => $this->string(),
        ]);

        $this->addForeignKey('fk_kid_absence_kid_id', '{{%kid_absence}}', 'kid_id', '{{%kid}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_kid_absence_event_id', '{{%kid_absence}}', 'event_id', '{{%event}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%kid_absence}}');
    }
}
