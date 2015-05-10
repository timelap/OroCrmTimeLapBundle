<?php

namespace RA\OroCrmTimeLapBundle\Services\Impl;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Services\WorklogService;
use RA\OroCrmTimeLapBundle\Model\Worklog;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;
use RA\OroCrmTimeLapBundle\Model\WorklogFactoryInterface;
use RA\OroCrmTimeLapBundle\Model\TimeSpentFactoryInterface;

class WorklogServiceImpl implements WorklogService
{
    /**
     * @var WorklogRepository
     */
    private $worklogRepository;

    /**
     * @var WorklogFactoryInterface
     */
    private $worklogFactory;

    /**
     * @var TimeSpentFactoryInterface
     */
    private $timeSpentFactory;

    /**
     * @param WorklogRepository         $worklogRepository
     * @param WorklogFactoryInterface   $worklogFactory
     * @param TimeSpentFactoryInterface $timeSpentFactory
     */
    public function __construct(
        WorklogRepository $worklogRepository,
        WorklogFactoryInterface $worklogFactory,
        TimeSpentFactoryInterface $timeSpentFactory
    ) {
        $this->worklogRepository = $worklogRepository;
        $this->worklogFactory    = $worklogFactory;
        $this->timeSpentFactory  = $timeSpentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getWorklog($worklogId)
    {
        return $this->loadWorklog($worklogId);
    }

    /**
     * {@inheritdoc}
     */
    public function logWork($timeSpent, \DateTime $dateStarted, Task $task, User $user, $description = null)
    {
        $timeSpent = $this->timeSpentFactory->createFromString($timeSpent);
        $worklog = $this->worklogFactory
            ->create(
                $timeSpent,
                $dateStarted,
                $task,
                $user,
                $description
            );
        $this->worklogRepository->save($worklog);

        return $worklog->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function updateWorklog($worklogId, $timeSpent, \DateTime $dateStarted, $description = null)
    {
        $worklog = $this->loadWorklog($worklogId);
        $timeSpent = $this->timeSpentFactory->createFromString($timeSpent);
        $worklog->update($timeSpent, $dateStarted, $description);
        $this->worklogRepository->save($worklog);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteWorklog($worklogId)
    {
        $worklog = $this->loadWorklog($worklogId);
        $this->worklogRepository->delete($worklog);
    }

    /**
     * @param int $worklogId
     * @return Worklog
     * @throws \RuntimeException if Worklog not found
     */
    private function loadWorklog($worklogId)
    {
        $worklog = $this->worklogRepository->get($worklogId);
        if (null === $worklog) {
            throw new \RuntimeException('Worklog not found.');
        }

        return $worklog;
    }
}
