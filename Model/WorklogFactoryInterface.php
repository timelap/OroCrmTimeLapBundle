<?php

namespace RA\OroCrmTimeLapBundle\Model;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

interface WorklogFactoryInterface
{
    /**
     * @param TimeSpent $timeSpent
     * @param \DateTime $dateStarted
     * @param Task $task
     * @param User $user
     * @param null|string $description
     * @return Worklog
     */
    public function create(TimeSpent $timeSpent, \DateTime $dateStarted, Task $task, User $user, $description = null);
}
