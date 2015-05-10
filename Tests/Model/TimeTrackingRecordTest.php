<?php

namespace RA\OroCrmTimeLapBundle\Tests\Model;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;
use RA\OroCrmTimeLapBundle\Model\Tracker;

class TimeTrackingRecordTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $user = new User();
        $task = new Task();
        $timeTrackingRecord = new Tracker($user, $task);

        $this->assertNull($timeTrackingRecord->getId());
        $this->assertEquals($user, $timeTrackingRecord->getUser());
        $this->assertEquals($task, $timeTrackingRecord->getTask());
        $this->assertGreaterThanOrEqual(
            new \DateTime('now', new \DateTimeZone('UTC')),
            $timeTrackingRecord->getDateStarted()
        );
    }

    public function testGetSpentSeconds()
    {
        $user = new User();
        $task = new Task();
        $timeTrackingRecord = new Tracker($user, $task);
        $workTimeInSeconds = 2;
        sleep($workTimeInSeconds);
        $timeSpent = $timeTrackingRecord->getSpentSeconds();

        $this->assertGreaterThanOrEqual($workTimeInSeconds, $timeSpent);
    }
}
