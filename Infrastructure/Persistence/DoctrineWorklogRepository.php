<?php

namespace RA\OroCrmTimeLapBundle\Infrastructure\Persistence;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\Period;
use RA\OroCrmTimeLapBundle\Model\Worklog;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;

class DoctrineWorklogRepository extends EntityRepository implements WorklogRepository
{
    /**
     * {@inheritdoc}
     */
    public function save(Worklog $worklog)
    {
        $this->_em->persist($worklog);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Worklog $worklog)
    {
        $this->_em->remove($worklog);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function listAllFilteredByTask(Task $task)
    {
        return $this->findBy(['task' => $task->getId()]);
    }

    /**
     * {@inheritdoc}
     */
    public function listAllByUserAndPeriod(User $user, Period $period)
    {
        $criteria = Criteria::create();
        $expr = Criteria::expr();
        $criteria->where(
            $expr->andX(
                $expr->eq('user', $user),
                $expr->gte('dateStarted', $period->getBegin()),
                $expr->lte('dateStarted', $period->getEnd())
            )
        );
        $workLogs = $this->matching($criteria);
        return $workLogs;
    }
}
