<?php

namespace RA\OroCrmTimeLapBundle\Tests\Services\Impl;

use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

use Doctrine\Common\Collections\ArrayCollection;

use Oro\Bundle\LocaleBundle\Model\LocaleSettings;
use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\PeriodFactory;
use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\Worklog;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;
use RA\OroCrmTimeLapBundle\Services\Impl\TimeSheetServiceImpl;

class TimeSheetServiceImplTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TimeSheetServiceImpl
     */
    private $service;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|WorklogRepository
     * @Mock \RA\OroCrmTimeLapBundle\Model\WorklogRepository
     */
    private $worklogRepository;

    /**
     * @var PeriodFactory
     */
    private $periodFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|LocaleSettings
     * @Mock \Oro\Bundle\LocaleBundle\Model\LocaleSettings
     */
    private $localeSettings;

    protected function setUp()
    {
        MockAnnotations::init($this);
        $this->localeSettings->expects($this->any())->method('getTimeZone')->will($this->returnValue('UTC'));
        $this->periodFactory = new PeriodFactory($this->localeSettings);
        $this->service = new TimeSheetServiceImpl($this->worklogRepository, $this->periodFactory, $this->localeSettings);
    }

    public function testCreateCurrentMonthTimeSheetPerUser()
    {
        $task = new Task();
        $user = new User();
        $currentDateTime = new \DateTime('now');
        $worklogDateTime = clone $currentDateTime;
        $worklogs = new ArrayCollection(
            [
                new Worklog(new TimeSpent(7200), $worklogDateTime, $task, $user),
                new Worklog(new TimeSpent(28800), $worklogDateTime->add(new \DateInterval('P1D')), $task, $user),
                new Worklog(new TimeSpent(13500), $worklogDateTime->add(new \DateInterval('P4D')), $task, $user)
            ]
        );

        $this->worklogRepository->expects($this->once())->method('listAllByUserAndPeriod')
            ->with($user, $this->isInstanceOf('\RA\OroCrmTimeLapBundle\Model\Period'))
            ->will($this->returnValue($worklogs));

        $timesheet = $this->service->createCurrentMonthTimeSheetPerUser($user);

        $this->assertInstanceOf('\RA\OroCrmTimeLapBundle\Model\Timesheet', $timesheet);
        $this->assertEquals(new TimeSpent(49500), $timesheet->getTotalTimeSpent());
        $this->assertEquals(
            new TimeSpent(13500),
            $timesheet->getTotalTimeSpentPerDate($currentDateTime->add(new \DateInterval('P5D')))
        );
    }

    public function testCreateMonthTimeSheetPerUser()
    {
        $task = new Task();
        $user = new User();
        $currentDateTime = new \DateTime('2014-12-12');
        $date = sprintf('%d-%d', $currentDateTime->format('Y'), $currentDateTime->format('m'));

        $worklogs = new ArrayCollection(
            [
                new Worklog(new TimeSpent(7200), new \DateTime('2014-12-04'), $task, $user),
                new Worklog(new TimeSpent(28800), new \DateTime('2014-12-05'), $task, $user),
                new Worklog(new TimeSpent(13500), new \DateTime('2014-12-06'), $task, $user)
            ]
        );

        $this->worklogRepository->expects($this->once())->method('listAllByUserAndPeriod')
            ->with($user, $this->isInstanceOf('\RA\OroCrmTimeLapBundle\Model\Period'))
            ->will($this->returnValue($worklogs));

        $timesheet = $this->service->createMonthTimeSheetPerUser($user, $date);

        $this->assertInstanceOf('\RA\OroCrmTimeLapBundle\Model\Timesheet', $timesheet);
        $this->assertEquals(new TimeSpent(49500), $timesheet->getTotalTimeSpent());
        $this->assertEquals(
            new TimeSpent(13500),
            $timesheet->getTotalTimeSpentPerDate(new \DateTime('2014-12-06'))
        );
    }
}
