<?php

namespace RA\OroCrmTimeLapBundle\Model;

use Oro\Bundle\UserBundle\Entity\User;

use RA\OroCrmTimeLapBundle\Model\Timesheet\Task;
use RA\OroCrmTimeLapBundle\Model\Timesheet\TaskList;

class Timesheet
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var TaskList|Task[]
     */
    private $taskList;

    /**
     * @var Period
     */
    private $period;

    /**
     * @param User $user
     * @param TaskList $taskList
     */
    public function __construct(User $user, TaskList $taskList)
    {
        $this->user = $user;
        $this->taskList = $taskList;
        $this->period = $taskList->getPeriod();
    }

    /**
     * @return TaskList
     */
    public function listTasks()
    {
        return $this->taskList;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Period
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @return TimeSpent
     */
    public function getTotalTimeSpent()
    {
        $total = 0;
        foreach ($this->taskList as $task) {
            $total += $task->getTotalTimeSpent()->getValue();
        }

        return new TimeSpent($total);
    }

    /**
     * @param \DateTime $date
     * @return TimeSpent
     */
    public function getTotalTimeSpentPerDate(\DateTime $date)
    {
        $total = 0;
        foreach ($this->taskList as $task) {
            $total += $task->getTotalTimeSpentForDate($date)->getValue();
        }

        return new TimeSpent($total);
    }

    /**
     * Retrieves total time spent per week of give date
     * @param \DateTime $date
     * @return TimeSpent
     */
    public function getWeeklyTotalTimeSpentPerDate(\DateTime $date)
    {
        $monday = clone $date;
        $sunday = clone $date;

        $monday->setISODate($monday->format('Y'), $monday->format('W'), 1);
        $sunday->setISODate($sunday->format('Y'), $sunday->format('W'), 7);

        $monday->setTime(0, 0, 0);
        $sunday->setTime(23, 59, 59);

        $datePeriod = new \DatePeriod($monday, new \DateInterval('P1D'), $sunday);
        $total = 0;
        foreach ($datePeriod as $period) {
            $total += $this->getTotalTimeSpentPerDate($period)->getValue();
        }

        return new TimeSpent($total);
    }
}
