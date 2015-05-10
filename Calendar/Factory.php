<?php

namespace RA\OroCrmTimeLapBundle\Calendar;

use Oro\Bundle\LocaleBundle\Model\LocaleSettings;

use RA\OroCrmTimeLapBundle\Model\Period;

class Factory
{
    /**
     * @var LocaleSettings
     */
    private $localeSettings;

    /**
     * @param LocaleSettings $localeSettings
     */
    public function __construct(LocaleSettings $localeSettings)
    {
        $this->localeSettings = $localeSettings;
    }

    /**
     * @param Period $period
     * @return CalendarPeriod
     */
    public function createCalendarPeriod(Period $period)
    {
        $begin = $period->getBegin();
        $end = $period->getEnd();
        $begin->setTimezone(new \DateTimeZone($this->localeSettings->getTimeZone()));
        $end->setTimezone(new \DateTimeZone($this->localeSettings->getTimeZone()));
        return new CalendarPeriod($begin, $end);
    }
}
