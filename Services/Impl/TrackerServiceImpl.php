<?php

namespace RA\OroCrmTimeLapBundle\Services\Impl;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\TimeSpentFactoryInterface;
use RA\OroCrmTimeLapBundle\Model\TrackerFactoryInterface;
use RA\OroCrmTimeLapBundle\Model\Tracker;
use RA\OroCrmTimeLapBundle\Model\TrackerRepository;
use RA\OroCrmTimeLapBundle\Model\WorklogFactoryInterface;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;
use RA\OroCrmTimeLapBundle\Services\TrackerService;

class TrackerServiceImpl implements TrackerService
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
     * @var TrackerRepository
     */
    private $trackerRepository;

    /**
     * @var TrackerFactoryInterface
     */
    private $trackerFactory;

    /**
     * @param WorklogRepository $worklogRepository
     * @param WorklogFactoryInterface $worklogFactory
     * @param TimeSpentFactoryInterface $timeSpentFactory
     * @param TrackerRepository $trackerRepository
     * @param TrackerFactoryInterface $trackerFactory
     */
    public function __construct(
        WorklogRepository $worklogRepository,
        WorklogFactoryInterface $worklogFactory,
        TimeSpentFactoryInterface $timeSpentFactory,
        TrackerRepository $trackerRepository,
        TrackerFactoryInterface $trackerFactory
    ) {
        $this->worklogRepository = $worklogRepository;
        $this->worklogFactory    = $worklogFactory;
        $this->timeSpentFactory  = $timeSpentFactory;
        $this->trackerRepository = $trackerRepository;
        $this->trackerFactory    = $trackerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getTracker(User $user)
    {
        $tracker = $this->trackerRepository->retrieveUserTracker($user);
        return $tracker;
    }

    /**
     * {@inheritdoc}
     */
    public function startTracking(User $user, Task $task)
    {
        $tracker = $this->trackerRepository->retrieveUserTracker($user);

        if ($tracker && $tracker->getTask()->getId() === $task->getId()) {
            return;
        }

        if ($tracker) {
            // stop previous tracker
            $this->processStopTracking($tracker);
        }

        $tracker = $this->trackerFactory->create($user, $task);
        $this->trackerRepository->save($tracker);
    }

    /**
     * {@inheritdoc}
     */
    public function stopTracking(User $user)
    {
        $tracker = $this->trackerRepository->retrieveUserTracker($user);
        if ($tracker) {
            $this->processStopTracking($tracker);
        }
    }

    /**
     * @param Tracker $tracker
     */
    private function processStopTracking(Tracker $tracker)
    {
        $this->trackerRepository->removeTracker($tracker);
        $timeSpent = $this->timeSpentFactory->create($tracker->getSpentSeconds());
        $worklog = $this->worklogFactory
            ->create(
                $timeSpent,
                $tracker->getDateStarted(),
                $tracker->getTask(),
                $tracker->getUser()
            );
        $this->worklogRepository->save($worklog);
    }
}
