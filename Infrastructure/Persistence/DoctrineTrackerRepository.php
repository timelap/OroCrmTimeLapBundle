<?php

namespace RA\OroCrmTimeLapBundle\Infrastructure\Persistence;

use Doctrine\ORM\EntityRepository;

use Oro\Bundle\UserBundle\Entity\User;

use RA\OroCrmTimeLapBundle\Model\Tracker;
use RA\OroCrmTimeLapBundle\Model\TrackerRepository;

class DoctrineTrackerRepository extends EntityRepository implements TrackerRepository
{
    /**
     * {@inheritdoc}
     */
    public function retrieveUserTracker(User $user)
    {
        return $this->findOneBy(['user' => $user]);
    }

    /**
     * {@inheritdoc}
     */
    public function save(Tracker $tracker)
    {
        $this->_em->persist($tracker);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function removeTracker(Tracker $tracker)
    {
        $this->_em->remove($tracker);
        $this->_em->flush();
    }
}
