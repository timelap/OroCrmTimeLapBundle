<?php

namespace RA\OroCrmTimeLapBundle\Infrastructure;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Entity\Worklog;
use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\WorklogFactoryInterface;

class WorklogFactory implements WorklogFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(TimeSpent $timeSpent, \DateTime $dateStarted, Task $task, User $user, $description = null)
    {
        return new Worklog($timeSpent, $dateStarted, $task, $user, $description);
    }
}
