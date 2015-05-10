<?php

namespace RA\OroCrmTimeLapBundle\Model;

class Period implements PeriodInterface
{
    const DEFAULT_TIMEZONE = 'UTC';

    /**
     * @var \DateTime
     */
    private $begin;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @param \DateTime $begin should be in UTC date time zone, if not - it will be cast to UTC
     * @param \DateTime $end should be in UTC date time zone, if not - it will be cast to UTC
     */
    public function __construct(\DateTime $begin, \DateTime $end)
    {
        if ($begin > $end) {
            throw new \InvalidArgumentException('Begin date can not be greater than end date.');
        }
        $timezone = new \DateTimeZone(self::DEFAULT_TIMEZONE);
        $this->begin = clone $begin;
        $this->end = clone $end;
        $this->begin->setTimezone($timezone);
        $this->end->setTimezone($timezone);
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
}
