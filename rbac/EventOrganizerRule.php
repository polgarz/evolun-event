<?php
namespace evolun\event\rbac;

use yii\rbac\Rule;
use yii\helpers\ArrayHelper;

/**
 * Checks if authorID matches user passed via params
 */
class EventOrganizerRule extends Rule
{
    public $name = 'isEventOrganizer';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['event'])) {
            return $params['event']->organizer_user_id === $user;
        }

        return false;
    }
}