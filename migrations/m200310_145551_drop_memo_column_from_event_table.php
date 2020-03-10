<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%event}}`.
 */
class m200310_145551_drop_memo_column_from_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('event', 'memo');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('event', 'memo', $this->text());
    }
}
