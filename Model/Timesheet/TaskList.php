<?php

namespace RA\OroCrmTimeLapBundle\Model\Timesheet;

use Doctrine\Common\Collections\Collection;

use RA\OroCrmTimeLapBundle\Model\Period;
use RA\OroCrmTimeLapBundle\Model\Worklog;

class TaskList implements \IteratorAggregate
{
    /**
     * @var Period
     */
    private $period;

    /**
     * @var array
     */
    private $elements;

    /**
     * @var \DateTimeZone
     */
    private $timezone;

    /**
     * @param Period $period
     * @param Collection $worklogs
     * @param \DateTimeZone $timezone
     */
    public function __construct(Period $period, Collection $worklogs, \DateTimeZone $timezone)
    {
        $this->period = $period;
        $this->elements = [];
        $this->timezone = $timezone;
        $this->initialize($worklogs);
    }

    /**
     * @param Collection $worklogs
     * @return void
     */
    private function initialize(Collection $worklogs)
    {
        /** @var \Iterator $iterator */
        $iterator = $worklogs->getIterator();
        $iterator->rewind();
        if ($iterator->valid()) {
            while ($iterator->valid())  {
                /** @var Worklog $worklog */
                $worklog = $iterator->current();
                if (!array_key_exists($worklog->getTask()->getId(), $this->elements)) {
                    $this->elements[$worklog->getTask()->getId()] = new Task($worklog->getTask(), $this->period);
                }
                /** @var Task $task */
                $task = $this->elements[$worklog->getTask()->getId()];
                /** @var \DateTime $date */
                $date = clone $worklog->getDateStarted();
                $date->setTimezone($this->timezone);
                $task->addTimeSpent($date, $worklog->getTimeSpent());
                $iterator->next();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @return Period
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->elements) === 0;
    }
}
