<?php

namespace RA\OroCrmTimeLapBundle\Tests\Model\Timesheet;

use OroCRM\Bundle\TaskBundle\Entity\Task as TaskEntity;

use RA\OroCrmTimeLapBundle\Model\Period;
use RA\OroCrmTimeLapBundle\Model\Timesheet\Task;
use RA\OroCrmTimeLapBundle\Model\TimeSpent;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2014-12-31'));
        $taskEntity = new TaskEntity();
        $taskEntity->setSubject('Subject');
        $task = new Task($taskEntity, $period);
        $this->assertEquals('Subject', $task->getSubject());
    }

    public function testGetTotalTimeSpentForDate()
    {
        $taskEntity = new TaskEntity();
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2014-12-31'));
        $task = new Task($taskEntity, $period);

        $task->addTimeSpent(new \DateTime('2014-12-03'), new TimeSpent(120));
        $task->addTimeSpent(new \DateTime('2014-12-04'), new TimeSpent(180));
        $task->addTimeSpent(new \DateTime('2014-12-04'), new TimeSpent(240));
        $task->addTimeSpent(new \DateTime('2014-12-05'), new TimeSpent(240));

        $this->assertEquals(new TimeSpent(120), $task->getTotalTimeSpentForDate(new \DateTime('2014-12-03')));
        $this->assertEquals(new TimeSpent(420), $task->getTotalTimeSpentForDate(new \DateTime('2014-12-04')));
        $this->assertEquals(new TimeSpent(240), $task->getTotalTimeSpentForDate(new \DateTime('2014-12-05')));
    }

    public function testGetTotalTimeSpent()
    {
        $taskEntity = new TaskEntity();
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2014-12-31'));
        $task = new Task($taskEntity, $period);

        $task->addTimeSpent(new \DateTime('2014-12-03'), new TimeSpent(120));
        $task->addTimeSpent(new \DateTime('2014-12-04'), new TimeSpent(180));
        $task->addTimeSpent(new \DateTime('2014-12-04'), new TimeSpent(240));
        $task->addTimeSpent(new \DateTime('2014-12-05'), new TimeSpent(240));

        $this->assertEquals(new TimeSpent(780), $task->getTotalTimeSpent());
    }
}
