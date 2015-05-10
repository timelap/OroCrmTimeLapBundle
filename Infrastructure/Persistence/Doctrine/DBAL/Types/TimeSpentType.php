<?php

namespace RA\OroCrmTimeLapBundle\Infrastructure\Persistence\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

use RA\OroCrmTimeLapBundle\Model\TimeSpent;

class TimeSpentType extends IntegerType
{
    const TIME_SPENT_TYPE = 'time_spent';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::TIME_SPENT_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new TimeSpent((int) $value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value) {
            return '';
        }
        if (!$value instanceof TimeSpent) {
            throw new \RuntimeException('Value should be a Time Spent type.');
        }
        return parent::convertToDatabaseValue($value->getValue(), $platform);
    }
}
