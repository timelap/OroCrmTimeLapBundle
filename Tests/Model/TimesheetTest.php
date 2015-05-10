<?php

namespace RA\OroCrmTimeLapBundle\Tests\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;
use RA\OroCrmTimeLapBundle\Model\Period;
use RA\OroCrmTimeLapBundle\Model\Timesheet;
use RA\OroCrmTimeLapBundle\Model\Timesheet\TaskList;
use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\Worklog;

class TimesheetTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateTimesheet()
    {
        $user = new User();
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2015-01-01'));
        $worklogs = new ArrayCollection([]);
        $taskList = new TaskList($period, $worklogs, new \DateTimeZone('UTC'));
        $timesheet = new Timesheet($user, $taskList);

        $this->assertEquals($taskList, $timesheet->listTasks());
        $this->assertEquals($user, $timesheet->getUser());
        $this->assertEquals($period, $timesheet->getPeriod());
    }

    public function testTotalTimeSpent()
    {
        $user = new User();
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2015-01-01'));
        $worklogs = new ArrayCollection([
            new Worklog(new TimeSpent(10), new \DateTime('2014-12-02'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(15), new \DateTime('2014-12-03'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(20), new \DateTime('2014-12-04'), new Task(), $user, 'description')
        ]);
        $taskList = new TaskList($period, $worklogs, new \DateTimeZone('UTC'));
        $timesheet = new Timesheet($user, $taskList);

        $this->assertEquals(new TimeSpent(45), $timesheet->getTotalTimeSpent());
    }

    public function testTotalTimeSpentPerDate()
    {
        $user = new User();
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2015-01-01'));
        $worklogs = new ArrayCollection([
            new Worklog(new TimeSpent(10), new \DateTime('2014-12-02'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(15), new \DateTime('2014-12-03'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(20), new \DateTime('2014-12-03'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(20), new \DateTime('2014-12-04'), new Task(), $user, 'description')
        ]);
        $taskList = new TaskList($period, $worklogs, new \DateTimeZone('UTC'));
        $timesheet = new Timesheet($user, $taskList);

        $this->assertEquals(new TimeSpent(10), $timesheet->getTotalTimeSpentPerDate(new \DateTime('2014-12-02')));
        $this->assertEquals(new TimeSpent(35), $timesheet->getTotalTimeSpentPerDate(new \DateTime('2014-12-03')));
        $this->assertEquals(new TimeSpent(20), $timesheet->getTotalTimeSpentPerDate(new \DateTime('2014-12-04')));
    }

    public function testWeeklyTotalTimeSpentPerDate()
    {
        $user = new User();
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2015-01-01'));
        $worklogs = new ArrayCollection([
            new Worklog(new TimeSpent(10), new \DateTime('2014-12-02'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(15), new \DateTime('2014-12-03'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(20), new \DateTime('2014-12-04'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(8), new \DateTime('2014-12-10'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(16), new \DateTime('2014-12-12'), new Task(), $user, 'description'),
            new Worklog(new TimeSpent(24), new \DateTime('2014-12-12'), new Task(), $user, 'description')
        ]);
        $taskList = new TaskList($period, $worklogs, new \DateTimeZone('UTC'));
        $timesheet = new Timesheet($user, $taskList);

        $this->assertEquals(new TimeSpent(45), $timesheet->getWeeklyTotalTimeSpentPerDate(new \DateTime('2014-12-04')));
        $this->assertEquals(new TimeSpent(45), $timesheet->getWeeklyTotalTimeSpentPerDate(new \DateTime('2014-12-07')));
        $this->assertEquals(new TimeSpent(48), $timesheet->getWeeklyTotalTimeSpentPerDate(new \DateTime('2014-12-11')));
    }
}
