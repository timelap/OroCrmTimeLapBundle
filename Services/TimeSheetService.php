<?php

namespace RA\OroCrmTimeLapBundle\Services;

use Oro\Bundle\UserBundle\Entity\User;

use RA\OroCrmTimeLapBundle\Model\Timesheet;

interface TimeSheetService
{
    /**
     * @param User $user
     * @return Timesheet
     */
    public function createCurrentMonthTimeSheetPerUser(User $user);

    /**
     * @param User $user
     * @param string $date
     * @return Timesheet
     */
    public function createMonthTimeSheetPerUser(User $user, $date);
}
