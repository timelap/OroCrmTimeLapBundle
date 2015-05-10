<?php

namespace RA\OroCrmTimeLapBundle\Model\Timesheet;

use OroCRM\Bundle\TaskBundle\Entity\Task as TaskEntity;

use RA\OroCrmTimeLapBundle\Model\Period;
use RA\OroCrmTimeLapBundle\Model\TimeSpent;

class Task implements \Iterator
{
    const DATE_FORMAT = 'Y-m-d';

    /**
     * @var TaskEntity
     */
    private $task;

    /**
     * @var Period
     */
    private $period;

    /**
     * @var \DateTime
     */
    private $position;

    /**
     * @var array
     */
    private $timeSpentPerDateList;

    /**
     * @param TaskEntity $task
     * @param Period $period
     */
    public function __construct(TaskEntity $task, Period $period)
    {
        $this->task = $task;
        $this->period = $period;
        $this->position = clone $period->getBegin();
        $this->timeSpentPerDateList = [];
    }

    /**
     * @param \DateTime $date
     * @param TimeSpent $timeSpent
     * @return void
     */
    public function addTimeSpent(\DateTime $date, TimeSpent $timeSpent)
    {
        if (!array_key_exists($date->format(self::DATE_FORMAT), $this->timeSpentPerDateList)) {
            $this->timeSpentPerDateList[$date->format(self::DATE_FORMAT)] = 0;
        }
        $this->timeSpentPerDateList[$date->format(self::DATE_FORMAT)] += $timeSpent->getValue();
    }

    /**
     * @param \DateTime $date
     * @return TimeSpent
     */
    public function getTotalTimeSpentForDate(\DateTime $date)
    {
        $timeSpent = 0;
        if (array_key_exists($date->format(self::DATE_FORMAT), $this->timeSpentPerDateList)) {
            $timeSpent = (int) $this->timeSpentPerDateList[$date->format(self::DATE_FORMAT)];
        }
        return new TimeSpent($timeSpent);
    }

    /**
     * @return TimeSpent
     */
    public function getTotalTimeSpent()
    {
        $total = 0;
        foreach ($this->timeSpentPerDateList as $timeSpent)  {
            $total += $timeSpent;
        }
        return new TimeSpent($total);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->task->getId();
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->task->getSubject();
    }

    /**
     * Return the current element
     * @return int
     */
    public function current()
    {
        return $this->getTotalTimeSpent($this->position);
    }

    /**
     * Move forward to next element
     * @return void
     */
    public function next()
    {
        $this->position->add(new \DateInterval('P1D'));
    }

    /**
     * Return the key of the current element
     * @return string
     */
    public function key()
    {
        return $this->position->format(self::DATE_FORMAT);
    }

    /**
     * Checks if current position is valid
     * @return boolean
     */
    public function valid()
    {
        return $this->position->getTimestamp() > $this->period->getEnd()->getTimestamp();
    }

    /**
     * Rewind the Iterator to the first element
     * @return void
     */
    public function rewind()
    {
        $this->position = clone $this->period->getBegin();
    }
}
