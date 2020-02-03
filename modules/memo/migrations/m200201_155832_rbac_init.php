<?php

use yii\db\Migration;

/**
 * Class m200201_155832_rbac_init
 */
class m200201_155832_rbac_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->getRole('admin');
        $manageEvents = $auth->getPermission('manageEvents');

        $rule = new \evolun\event\rbac\EventParticipateRule;
        $auth->add($rule);

        $editEventMemo = $auth->createPermission('editEventMemo');
        $editEventMemo->description = 'Szerkesztheti az események beszámolóit, amennyiben résztvevő';
        $editEventMemo->ruleName = $rule->name;
        $auth->add($editEventMemo);

        $auth->addChild($editEventMemo, $manageEvents);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $editEventMemo = $auth->getPermission('editEventMemo');
        $auth->remove($editEventMemo);
        $isEventParticipate = $auth->getRule('isEventParticipate');
        $auth->remove($isEventParticipate);
    }
}
