<?php

namespace RA\OroCrmTimeLapBundle\Tests\Model;

use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\Worklog;
use RA\OroCrmTimeLapBundle\Model\TotalTimeSpentCalculator;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;

class TotalTimeSpentCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TotalTimeSpentCalculator
     */
    private $calculator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|WorklogRepository
     * @Mock \RA\OroCrmTimeLapBundle\Model\WorklogRepository
     */
    private $worklogRepository;

    protected function setUp()
    {
        MockAnnotations::init($this);
        $this->calculator = new TotalTimeSpentCalculator($this->worklogRepository);
    }

    public function testCalculatePerTask()
    {
        $task = new Task();
        $worklogs = [
            new Worklog(new TimeSpent(5), new \DateTime('now'), $task, new User()),
            new Worklog(new TimeSpent(3), new \DateTime('now'), $task, new User())
        ];

        $this->worklogRepository->expects($this->once())->method('listAllFilteredByTask')
            ->with($this->equalTo($task))
            ->will($this->returnValue($worklogs));

        $total = $this->calculator->calculatePerTask($task);

        $this->assertEquals(8, $total->getValue());
        $this->assertEquals('8s', (string) $total);
    }
}
