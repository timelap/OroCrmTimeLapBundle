<?php

namespace RA\OroCrmTimeLapBundle\Model;

interface TimeSpentFactoryInterface
{
    /**
     * @param int $seconds
     * @return TimeSpent
     */
    public function create($seconds);

    /**
     * @param string $input
     * @return TimeSpent
     */
    public function createFromString($input);
}
