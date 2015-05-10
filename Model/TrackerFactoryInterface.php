<?php

namespace RA\OroCrmTimeLapBundle\Model;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

interface TrackerFactoryInterface
{
    /**
     * @param User $user
     * @param Task $task
     * @return Tracker
     */
    public function create(User $user, Task $task);
}
