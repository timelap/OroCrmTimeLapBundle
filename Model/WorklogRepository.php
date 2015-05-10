<?php

namespace RA\OroCrmTimeLapBundle\Model;

use Doctrine\Common\Collections\Collection;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

interface WorklogRepository
{
    /**
     * @param Worklog $worklog
     * @return void
     */
    public function save(Worklog $worklog);

    /**
     * @param int $id
     * @return Worklog
     */
    public function get($id);

    /**
     * @param Worklog $worklog
     * @return void
     */
    public function delete(Worklog $worklog);

    /**
     * @param Task $task
     * @return Worklog[]
     */
    public function listAllFilteredByTask(Task $task);

    /**
     * @param User $user
     * @param Period $period
     * @return Collection|Worklog[]
     */
    public function listAllByUserAndPeriod(User $user, Period $period);
}
