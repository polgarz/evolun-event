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
        $manageEvents->description = 'Hozzáadhat, módosíthat, és törölhet eseményeket';
        $auth->add($manageEvents);

        $showEvents = $auth->createPermission('showEvents');
        $showEvents->description = 'Megtekintheti az eseményeket';
        $auth->add($showEvents);

        $setOrganizer = $auth->createPermission('setOrganizer');
        $setOrganizer->description = 'Beállíthatja magát eseményhez, mint szervező';
        $auth->add($setOrganizer);

        $manageOrganizedEvents = $auth->createPermission('manageOrganizedEvents');
        $manageOrganizedEvents->description = 'Szerkesztheti azoknak az eseménynek a részleteit, amit ő szervez';
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
