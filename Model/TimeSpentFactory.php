<?php

namespace RA\OroCrmTimeLapBundle\Model;

class TimeSpentFactory implements TimeSpentFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($seconds)
    {
        return new TimeSpent($seconds);
    }

    /**
     * {@inheritdoc}
     */
    public function createFromString($input)
    {
        $input = (string) $input;
        if (false === TimeSpent::isValid($input)) {
            throw new TimeSpentInvalidFormatException('Input format is invalid.');
        }
        $total = 0;
        foreach (explode(' ', $input) as $part) {
            $valueInSeconds = 0;
            $value = preg_replace('/[^0-9]/', '', $part);
            if (strpos($part, 'd')) {
                $valueInSeconds = $value * TimeSpent::DAY;
            } elseif (strpos($part, 'h')) {
                $valueInSeconds = $value * TimeSpent::HOUR;
            } elseif (strpos($part, 'm')) {
                $valueInSeconds = $value * TimeSpent::MINUTE;
            } elseif (strpos($part, 's')) {
                $valueInSeconds = $value;
            }
            $total += $valueInSeconds;
        }
        return $this->create($total);
    }
}
