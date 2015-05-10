<?php

namespace RA\OroCrmTimeLapBundle\Infrastructure;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Entity\Tracker;
use RA\OroCrmTimeLapBundle\Model\TrackerFactoryInterface;

class TrackerFactory implements TrackerFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(User $user, Task $task)
    {
        return new Tracker($user, $task);
    }
}
