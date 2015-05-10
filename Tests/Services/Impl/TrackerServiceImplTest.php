<?php

namespace RA\OroCrmTimeLapBundle\Tests\Services\Impl;

use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\TimeSpentFactoryInterface;
use RA\OroCrmTimeLapBundle\Model\Tracker;
use RA\OroCrmTimeLapBundle\Model\TrackerFactoryInterface;
use RA\OroCrmTimeLapBundle\Model\TrackerRepository;
use RA\OroCrmTimeLapBundle\Model\Worklog;
use RA\OroCrmTimeLapBundle\Model\WorklogFactoryInterface;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;
use RA\OroCrmTimeLapBundle\Services\Impl\TrackerServiceImpl;

class TrackerServiceImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TrackerServiceImpl
     */
    private $service;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|WorklogRepository
     * @Mock \RA\OroCrmTimeLapBundle\Model\WorklogRepository
     */
    private $worklogRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|WorklogFactoryInterface
     * @Mock \RA\OroCrmTimeLapBundle\Model\WorklogFactoryInterface
     */
    private $worklogFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TimeSpentFactoryInterface
     * @Mock \RA\OroCrmTimeLapBundle\Model\TimeSpentFactoryInterface
     */
    private $timeSpentFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TrackerRepository
     * @Mock \RA\OroCrmTimeLapBundle\Model\TrackerRepository
     */
    private $trackerRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TrackerFactoryInterface
     * @Mock \RA\OroCrmTimeLapBundle\Model\TrackerFactoryInterface
     */
    private $trackerFactory;

    protected function setUp()
    {
        MockAnnotations::init($this);
        $this->service = new TrackerServiceImpl(
            $this->worklogRepository,
            $this->worklogFactory,
            $this->timeSpentFactory,
            $this->trackerRepository,
            $this->trackerFactory
        );
    }

    public function testGetTracker()
    {
        $user = new User();
        $task = new Task();
        $tracker = new Tracker($user, $task, new \DateTime('now'));

        $this->trackerRepository->expects($this->once())->method('retrieveUserTracker')
            ->with($user)->will($this->returnValue($tracker));

        $result = $this->service->getTracker($user);

        $this->assertEquals($tracker, $result);
    }

    public function testStartTrackingWhenThereIsNoUserTracker()
    {
        $user = new User();
        $task = new Task();
        $tracker = new Tracker($user, $task);

        $this->trackerRepository->expects($this->once())->method('retrieveUserTracker')
            ->with($user)
            ->will($this->returnValue(null));

        $this->trackerFactory->expects($this->once())->method('create')
            ->with($user, $task)->will($this->returnValue($tracker));

        $this->trackerRepository->expects($this->once())->method('save')->with($tracker);

        $this->service->startTracking($user, $task);
    }

    public function testStartTrackingWhenUserTrackingSameTask()
    {
        $user = new User();
        $task = new Task();

        $tracker = new Tracker($user, $task);

        $this->trackerRepository->expects($this->once())->method('retrieveUserTracker')
            ->with($user)
            ->will($this->returnValue($tracker));

        $this->trackerRepository->expects($this->never())->method('save');

        $this->service->startTracking($user, $task);
    }

    public function testStartTrackingWhenUserTrackingOtherTask()
    {
        $user = new User();
        $task = new Task();
        $task->setId(1);
        $otherTask = new Task();
        $otherTask->setId(2);
        $waitTimeInSeconds = 2;

        $trackerOfOtherTask = new Tracker($user, $otherTask);
        sleep($waitTimeInSeconds);

        $tracker = new Tracker($user, $task);
        $timeSpent = new TimeSpent($tracker->getSpentSeconds());

        $worklog = new Worklog(
            $timeSpent,
            $trackerOfOtherTask->getDateStarted(),
            $trackerOfOtherTask->getTask(),
            $trackerOfOtherTask->getUser()
        );

        $this->trackerRepository->expects($this->once())->method('retrieveUserTracker')
            ->with($user)
            ->will($this->returnValue($trackerOfOtherTask));

        $this->timeSpentFactory->expects($this->once())->method('create')
            ->with($trackerOfOtherTask->getSpentSeconds())
            ->will($this->returnValue($timeSpent));

        $this->worklogFactory->expects($this->once())->method('create')
            ->with($timeSpent, $trackerOfOtherTask->getDateStarted(), $otherTask, $user)
            ->will($this->returnValue($worklog));

        $this->trackerFactory->expects($this->once())->method('create')
            ->with($user, $task)->will($this->returnValue($tracker));

        $this->trackerRepository->expects($this->once())->method('save')->with($tracker);

        $this->trackerRepository->expects($this->once())->method('removeTracker')
            ->with($trackerOfOtherTask);

        $this->worklogRepository->expects($this->once())->method('save')->with($worklog);

        $this->service->startTracking($user, $task);
    }

    public function testStopTracking()
    {
        $user = new User();
        $task = new Task();
        $waitTimeInSeconds = 2;

        $tracker = new Tracker($user, $task);
        sleep($waitTimeInSeconds);

        $timeSpent = new TimeSpent($waitTimeInSeconds);

        $worklog = new Worklog(
            $timeSpent,
            $tracker->getDateStarted(),
            $task,
            $user
        );

        $this->trackerRepository->expects($this->once())->method('retrieveUserTracker')
            ->with($user)
            ->will($this->returnValue($tracker));

        $this->trackerRepository->expects($this->once())->method('removeTracker')->with($tracker);

        $this->timeSpentFactory->expects($this->once())->method('create')
            ->with($tracker->getSpentSeconds())
            ->will($this->returnValue($timeSpent));

        $this->worklogFactory->expects($this->once())->method('create')
            ->with($timeSpent, $tracker->getDateStarted(), $task, $user)
            ->will($this->returnValue($worklog));

        $this->worklogRepository->expects($this->once())->method('save')->with();

        $this->service->stopTracking($user);
    }
}
