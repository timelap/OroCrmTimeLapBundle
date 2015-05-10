<?php

namespace RA\OroCrmTimeLapBundle\Model;

use Oro\Bundle\LocaleBundle\Model\LocaleSettings;

class PeriodFactory
{
    /**
     * @var \DateTimeZone
     */
    private $timezone;

    /**
     * @param LocaleSettings $localeSettings
     */
    public function __construct(LocaleSettings $localeSettings)
    {
        $this->timezone = new \DateTimeZone($localeSettings->getTimeZone());
    }

    /**
     * @return Period
     */
    public function currentMonthPeriod()
    {
        return $this->create(new \DateTime('now', $this->timezone));
    }

    /**
     * @param string $periodRequest
     * @return Period
     */
    public function monthPeriod($periodRequest)
    {
        $date = \DateTime::createFromFormat(
            'Y-m-d h:i:s', sprintf('%s-01 00:00:00', $periodRequest),
            $this->timezone
        );
        if (false === $date) {
            throw new \InvalidArgumentException('Given period request has invalid format.');
        }
        return $this->create($date);
    }

    /**
     * @param \DateTime $date
     * @return Period
     */
    private function create(\DateTime $date)
    {
        $begin = clone $date;
        $begin->setTime(0, 0, 0);
        $end = clone $begin;
        $end->setTime(23, 59, 59);
        $begin->modify('first day of this month');
        $end->modify('last day of this month');
        $period = new Period($begin, $end);
        return $period;
    }
}
