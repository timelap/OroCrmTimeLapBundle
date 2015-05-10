<?php

namespace RA\OroCrmTimeLapBundle\Model;

use OroCRM\Bundle\TaskBundle\Entity\Task;

class TotalTimeSpentCalculator
{
    /**
     * @var WorklogRepository
     */
    private $worklogRepository;

    /**
     * @param WorklogRepository $worklogRepository
     */
    public function __construct(WorklogRepository $worklogRepository)
    {
        $this->worklogRepository = $worklogRepository;
    }

    /**
     * @param array|Worklog[] $list
     * @return TimeSpent
     */
    private function calculate(array $list)
    {
        $total = 0;
        foreach ($list as $worklog) {
            $total += $worklog->getTimeSpent()->getValue();
        }
        return new TimeSpent($total);
    }

    /**
     * Calculate total Time Spent for the Task
     * @param Task $task
     * @return TimeSpent
     */
    public function calculatePerTask(Task $task)
    {
        $list = $this->worklogRepository->listAllFilteredByTask($task);
        return $this->calculate($list);
    }
}
