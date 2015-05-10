<?php

namespace RA\OroCrmTimeLapBundle\Calendar;

use RA\OroCrmTimeLapBundle\Model\Period;

class Calendar
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Period $period
     * @return CalendarPeriod
     */
    public function getCalendarPeriod(Period $period)
    {
        return $this->factory->createCalendarPeriod($period);
    }
}
