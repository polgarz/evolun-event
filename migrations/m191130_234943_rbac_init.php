<?php

use yii\db\Migration;

/**
 * Class m191130_234943_rbac_init
 */
class m191130_234943_rbac_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $admin = $auth->getRole('admin');

        $rule = new \evolun\event\rbac\EventOrganizerRule;
        $auth->add($rule);

        $manageEvents = $auth->createPermission('manageEvents');
        $manageEvents->description = 'Can add, edit, or delete events';
        $auth->add($manageEvents);

        $showEvents = $auth->createPermission('showEvents');
        $showEvents->description = 'Can view events';
        $auth->add($showEvents);

        $setOrganizer = $auth->createPermission('setOrganizer');
        $setOrganizer->description = 'Can set him/herself as an organizer';
        $auth->add($setOrganizer);

        $manageOrganizedEvents = $auth->createPermission('manageOrganizedEvents');
        $manageOrganizedEvents->description = 'Can edit the details of the event he/she is organizing';
        $manageOrganizedEvents->ruleName = $rule->name;
        $auth->add($manageOrganizedEvents);

        $auth->addChild($admin, $showEvents);
        $auth->addChild($admin, $manageEvents);
        $auth->addChild($admin, $setOrganizer);
        $auth->addChild($manageOrganizedEvents, $manageEvents);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $manageEvents = $auth->getPermission('manageEvents');
        $auth->remove($manageEvents);
        $showEvents = $auth->getPermission('showEvents');
        $auth->remove($showEvents);
        $setOrganizer = $auth->getPermission('setOrganizer');
        $auth->remove($setOrganizer);
        $manageOrganizedEvents = $auth->getPermission('manageOrganizedEvents');
        $auth->remove($manageOrganizedEvents);
        $isEventOrganizer = $auth->getRule('isEventOrganizer');
        $auth->remove($isEventOrganizer);
    }
}
