<?php
namespace evolun\event\rbac;

use yii\rbac\Rule;
use yii\helpers\ArrayHelper;

/**
 * Checks if authorID matches user passed via params
 */
class EventParticipateRule extends Rule
{
    public $name = 'isEventParticipate';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['event'])) {
            return in_array($user, ArrayHelper::getColumn($params['event']->participates, 'user_id'));
        }

        return false;
    }
}
