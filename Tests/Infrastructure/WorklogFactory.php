<?php

namespace RA\OroCrmTimeLapBundle\Tests\Infrastructure;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;
use RA\OroCrmTimeLapBundle\Validator\Constraints\TimeSpent;

class WorklogFactory extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $factory = new \RA\OroCrmTimeLapBundle\Infrastructure\WorklogFactory();
        $worklog = $factory->create(
            new TimeSpent(10),
            new \DateTime('now', new \DateTimeZone('UTC')),
            new Task(),
            new User(),
            'Description'
        );

        $this->assertInstanceOf('RA\OroCrmTimeLapBundle\Entity\Worklog', $worklog);
    }
}
