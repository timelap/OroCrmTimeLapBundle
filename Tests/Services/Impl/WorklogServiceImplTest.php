<?php

namespace RA\OroCrmTimeLapBundle\Tests\Services\Impl;

use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\TimeSpentFactoryInterface;
use RA\OroCrmTimeLapBundle\Model\Worklog;
use RA\OroCrmTimeLapBundle\Model\WorklogFactoryInterface;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;
use RA\OroCrmTimeLapBundle\Services\Impl\WorklogServiceImpl;

class WorklogServiceImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WorklogServiceImpl
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

    protected function setUp()
    {
        MockAnnotations::init($this);
        $this->service = new WorklogServiceImpl(
            $this->worklogRepository,
            $this->worklogFactory,
            $this->timeSpentFactory
        );
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Worklog not found.
     */
    public function getWorklogWhenItIsNotExist()
    {
        $worklogId = 1;
        $this->worklogRepository->expects($this->once())->method('get')->with($this->equalTo($worklogId))
            ->will($this->returnValue(null));
        $this->service->getWorklog($worklogId);
    }

    /**
     * @test
     */
    public function thatWorklogRetireves()
    {
        $worklogId = 1;
        $worklog = new Worklog(new TimeSpent(5), new \DateTime('now'), new Task(), new User(), 'DESC');

        $this->worklogRepository->expects($this->once())->method('get')->with($this->equalTo($worklogId))
            ->will($this->returnValue($worklog));

        $result = $this->service->getWorklog($worklogId);

        $this->assertEquals($worklog, $result);
    }

    /**
     * @test
     */
    public function thatLogsWork()
    {
        $timeSpentInputString = '10m';
        $timeSpentInSeconds   = 600;
        $dateStarted = new \DateTime('now');
        $description = 'DESC';

        $task = new Task();
        $user = new User();

        $timeSpent = new TimeSpent($timeSpentInSeconds);
        $worklog = new Worklog($timeSpent, $dateStarted, $task, $user, $description);

        $this->timeSpentFactory->expects($this->once())->method('createFromString')->with('10m')
            ->will($this->returnValue($timeSpent));

        $this->worklogFactory->expects($this->once())->method('create')->with()
            ->will($this->returnValue($worklog));

        $this->worklogRepository->expects($this->once())->method('save')->with($worklog);

        $this->service->logWork($timeSpentInputString, $dateStarted, $task, $user, $description);
    }

    /**
     * @test
     */
    public function thatWorklogUpdates()
    {
        $worklogId = 1;
        $timeSpentInputString = '10m';
        $timeSpentInSeconds   = 600;
        $dateStarted = new \DateTime('now');
        $dateStarted->sub(new \DateInterval('P2D'));
        $description = 'UPDATED DESC';

        $timeSpent = new TimeSpent($timeSpentInSeconds);
        $worklog = new Worklog(new TimeSpent(300), new \DateTime('now'), new Task(), new User(), 'DESC');

        $this->worklogRepository->expects($this->once())->method('get')->with($this->equalTo($worklogId))
            ->will($this->returnValue($worklog));

        $this->timeSpentFactory->expects($this->once())->method('createFromString')->with($timeSpentInputString)
            ->will($this->returnValue($timeSpent));

        $this->worklogRepository->expects($this->once())->method('save')->with($this->equalTo($worklog));

        $this->service->updateWorklog($worklogId, $timeSpentInputString, $dateStarted, $description);

        $this->assertEquals($timeSpent, $worklog->getTimeSpent());
        $this->assertEquals($dateStarted, $worklog->getDateStarted());
        $this->assertEquals($description, $worklog->getDescription());
    }

    /**
     * @test
     */
    public function thatWorklogDeletes()
    {
        $worklogId = 1;
        $worklog = new Worklog(new TimeSpent(5), new \DateTime('now'), new Task(), new User(), 'DESC');

        $this->worklogRepository->expects($this->once())->method('get')->with($this->equalTo($worklogId))
            ->will($this->returnValue($worklog));

        $this->worklogRepository->expects($this->once())->method('delete')->with($this->equalTo($worklog));

        $this->service->deleteWorklog($worklogId);
    }


}
