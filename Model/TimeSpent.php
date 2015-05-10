<?php

namespace RA\OroCrmTimeLapBundle\Model;

class TimeSpent
{
    const TIME_SPENT_PATTERN = '/^\d+d(\s\d+h(\s\d+m)?)?$|^\d+h(\s\d+m)?$|^\d+m$/';

    const MINUTE = 60; // seconds in minute
    const HOUR   = 3600; // seconds in hour
    const DAY    = 86400; // seconds in day
    const MONTH  = 2592000; // seconds in month, where month has 30 days
    const YEAR   = 31536000; // seconds in year, where year has 365 days

    /**
     * @var int
     */
    private $seconds;

    /**
     * @param int $seconds
     */
    public function __construct($seconds)
    {
        if (false === is_int($seconds)) {
            throw new \InvalidArgumentException('Invalid given value of seconds.');
        }
        $this->seconds = $seconds;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->seconds;
    }

    /**
     * Format time spent value in seconds into string format (i.e. 2h 35m)
     * @return string
     */
    public function format()
    {
        $value = $this->seconds;

        $str = '';

        if (($value / self::DAY) >= 1) {
            $str .= (int) ($value / self::DAY) . 'd';
            $value -= (int) ($value / self::DAY) * self::DAY;
        }

        if (($value / self::HOUR) >= 1) {
            $str .= ' ' . (int) ($value / self::HOUR) . 'h';
            $value -= (int) ($value / self::HOUR) * self::HOUR;
        }

        if (($value / self::MINUTE) >= 1) {
            $str .= ' ' . (int) ($value / self::MINUTE) . 'm';
            $value -= (int) ($value / self::MINUTE) * self::MINUTE;
        }

        if ($value < self::MINUTE && $value > 0) {
            $str .= ' ' . $value . 's';
        }

        return trim($str);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }

    /**
     * Validate Time Spent string representation
     * @param string $input
     * @return bool
     */
    public static function isValid($input)
    {
        $isValid = preg_match(self::TIME_SPENT_PATTERN, $input);
        return (bool) $isValid;
    }
}
