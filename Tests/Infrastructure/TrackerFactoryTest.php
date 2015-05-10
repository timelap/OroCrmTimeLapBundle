<?php

namespace RA\OroCrmTimeLapBundle\Tests\Infrastructure;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Infrastructure\TrackerFactory;

class TrackerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $factory = new TrackerFactory();
        $tracker = $factory->create(new User(), new Task());

        $this->assertInstanceOf('RA\OroCrmTimeLapBundle\Entity\Tracker', $tracker);
    }
}
