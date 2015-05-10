<?php

namespace RA\OroCrmTimeLapBundle\Calendar;

use RA\OroCrmTimeLapBundle\Model\PeriodInterface;

class CalendarPeriod implements \Iterator, PeriodInterface
{
    /**
     * @var \DateTime
     */
    protected $begin;

    /**
     * @var \DateTime
     */
    protected $end;

    /**
     * @var \DateTime
     */
    private $day;

    /**
     * @param \DateTime $begin
     * @param \DateTime $end
     */
    public function __construct(\DateTime $begin, \DateTime $end)
    {
        $this->begin = $begin;
        $this->end = $end;
    }

    /**
     * {@inheritdoc}
     */
    public function getBegin()
    {
        return clone $this->begin;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnd()
    {
        return clone $this->end;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return clone $this->day;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        $this->day->add(new \DateInterval('P1D'));
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->day->format('d');
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return $this->day->getTimestamp() <= $this->end->getTimestamp();
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->day = clone $this->begin;
    }
}
