<?php

namespace RA\OroCrmTimeLapBundle\Twig;

use RA\OroCrmTimeLapBundle\Calendar\DatesHelper;

class CalendarExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'timelap_calendar';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'timelap_is_last_day_of_week',
                [$this, 'isALastDayOfTheWeek']
            ),
            new \Twig_SimpleFunction(
                'timelap_is_weekend',
                [$this, 'isWeekend']
            )
        ];
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function isALastDayOfTheWeek(\DateTime $date)
    {
        return DatesHelper::getIsALastDayOfTheWeek($date);
    }

    /**
     * @param \DateTime $date
     * @return bool
     */
    public function isWeekend(\DateTime $date)
    {
        return DatesHelper::getIsWeekend($date);
    }
}
