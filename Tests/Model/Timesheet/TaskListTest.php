<?php

namespace RA\OroCrmTimeLapBundle\Tests\Model\Timesheet;

use Doctrine\Common\Collections\ArrayCollection;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task as TaskEntity;

use RA\OroCrmTimeLapBundle\Model\Period;
use RA\OroCrmTimeLapBundle\Model\Timesheet\TaskList;
use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\Worklog;

class TaskListTest extends \PHPUnit_Framework_TestCase
{
    public function testCreates()
    {
        $taskEntity = new TaskEntity();
        $taskEntity->setId(1);
        $anotherTaskEntity = new TaskEntity();
        $anotherTaskEntity->setId(2);
        $user = new User();
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2014-12-31'));
        $worklogs = new ArrayCollection(
            [
                new Worklog(new TimeSpent(60), new \DateTime('2014-12-02'), $taskEntity, $user),
                new Worklog(new TimeSpent(120), new \DateTime('2014-12-03'), $taskEntity, $user),
                new Worklog(new TimeSpent(180), new \DateTime('2014-12-04'), $anotherTaskEntity, $user)
            ]
        );
        $taskList = new TaskList($period, $worklogs, new \DateTimeZone('UTC'));

        $this->assertEquals($period, $taskList->getPeriod());
        $this->assertInstanceOf('\Traversable', $taskList->getIterator());
        $this->assertFalse($taskList->isEmpty());
        $this->assertCount(2, $taskList->getIterator());

        foreach ($taskList as $each) {
            $this->assertInstanceOf('\RA\OroCrmTimeLapBundle\Model\TimeSheet\Task', $each);
        }
    }

    public function testIsEmpty()
    {
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2014-12-31'));
        $worklogs = new ArrayCollection([]);
        $taskList = new TaskList($period, $worklogs, new \DateTimeZone('UTC'));

        $this->assertTrue($taskList->isEmpty());
    }
}
