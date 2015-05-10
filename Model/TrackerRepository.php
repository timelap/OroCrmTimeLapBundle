<?php

namespace RA\OroCrmTimeLapBundle\Model;

use Oro\Bundle\UserBundle\Entity\User;

interface TrackerRepository
{
    /**
     * @param User $user
     * @return Tracker
     */
    public function retrieveUserTracker(User $user);

    /**
     * @param Tracker $tracker
     * @return void
     */
    public function save(Tracker $tracker);

    /**
     * @param Tracker $tracker
     * @return void
     */
    public function removeTracker(Tracker $tracker);
}
