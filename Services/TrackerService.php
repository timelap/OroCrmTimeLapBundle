<?php

namespace RA\OroCrmTimeLapBundle\Services;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\Tracker;

interface TrackerService
{
    /**
     * Retrieves current tracker for user and task
     * @param User $user
     * @return Tracker
     */
    public function getTracker(User $user);

    /**
     * Starts new tracker for user and task.
     * If system already has user tracker for the given task - just do nothing, continue tracking time
     * If system already has user tracker but for an other task - it should be stopped and new worklog created
     * @param User $user
     * @param Task $task
     * @return void
     */
    public function startTracking(User $user, Task $task);

    /**
     * Stops tracker for user, creates new worklog and delete the tracker
     * @param User $user
     * @return void
     */
    public function stopTracking(User $user);
}
