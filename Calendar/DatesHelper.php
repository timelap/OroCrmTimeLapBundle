<?php

namespace RA\OroCrmTimeLapBundle\Calendar;

class DatesHelper
{
    /**
     * @param \DateTime $dateTime
     * @return bool
     */
    public static function getIsWeekend(\DateTime $dateTime)
    {
        return ($dateTime->format('D') === 'Sun' || $dateTime->format('D') === 'Sat');
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public static function getIsALastDayOfTheWeek(\DateTime $date)
    {
        return (bool) ($date->format('D') === 'Sun');
    }
}
