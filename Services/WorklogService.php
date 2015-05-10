<?php

namespace RA\OroCrmTimeLapBundle\Services;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\Worklog;

interface WorklogService
{
    /**
     * Load worklog
     * @param int $worklogId
     * @return Worklog
     * @throws \RuntimeException if worklog not found
     */
    public function getWorklog($worklogId);

    /**
     * Log work
     * @param string $timeSpent
     * @param \DateTime $dateStarted
     * @param Task $task
     * @param User $user
     * @param null|string $description
     * @return int Worklog Id
     */
    public function logWork($timeSpent, \DateTime $dateStarted, Task $task, User $user, $description = null);

    /**
     * Update worklog
     * @param int $worklogId
     * @param string $timeSpent
     * @param \DateTime $dateStarted
     * @param null|string $description
     * @return void
     * @throws \RuntimeException if worklog not found
     */
    public function updateWorklog($worklogId, $timeSpent, \DateTime $dateStarted, $description = null);

    /**
     * Delete worklog
     * @param int $worklogId
     * @return void
     * @throws \RuntimeException if worklog not found
     */
    public function deleteWorklog($worklogId);
}
