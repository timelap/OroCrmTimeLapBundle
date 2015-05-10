<?php

namespace RA\OroCrmTimeLapBundle\Tests\Twig;

use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Oro\Bundle\AttachmentBundle\Manager\AttachmentManager;
use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\TrackerRepository;
use RA\OroCrmTimeLapBundle\Model\Worklog;
use RA\OroCrmTimeLapBundle\Twig\TimeTrackingExtension;
use RA\OroCrmTimeLapBundle\Model\Tracker;
use RA\OroCrmTimeLapBundle\Model\TotalTimeSpentCalculator;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;

class TimeTrackingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TimeTrackingExtension
     */
    private $twigExtension;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TotalTimeSpentCalculator
     * @Mock \RA\OroCrmTimeLapBundle\Model\TotalTimeSpentCalculator
     */
    private $calculator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|WorklogRepository
     * @Mock \RA\OroCrmTimeLapBundle\Model\WorklogRepository
     */
    private $worklogRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TrackerRepository
     * @Mock \RA\OroCrmTimeLapBundle\Model\TrackerRepository
     */
    private $trackerRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|RouterInterface
     * @Mock \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|AttachmentManager
     * @Mock \Oro\Bundle\AttachmentBundle\Manager\AttachmentManager
     */
    private $attachmentManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|SecurityContextInterface
     * @Mock \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TokenInterface
     * @Mock \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    private $token;

    protected function setUp()
    {
        MockAnnotations::init($this);
        $this->twigExtension = new TimeTrackingExtension(
            $this->calculator,
            $this->worklogRepository,
            $this->trackerRepository,
            $this->router,
            $this->attachmentManager,
            $this->securityContext
        );
    }

    public function testTaskWorklogTotalTimeSpent()
    {
        $task = new Task();

        $this->calculator->expects($this->once())->method('calculatePerTask')
            ->with($task)->will($this->returnValue(new TimeSpent(10)));

        $total = $this->twigExtension->taskWorklogTotalTimeSpent($task);

        $this->assertEquals('10s', $total);
    }

    /**
     * @test
     */
    public function taskWorklogEntries()
    {
        $task = new Task();
        $worklogs = [
            new Worklog(new TimeSpent(12), new \DateTime('now'), $task, new User(), 'DESC_ONE'),
            new Worklog(new TimeSpent(8), new \DateTime('now'), $task, new User(), 'DESC_TWO')
        ];

        $this->worklogRepository->expects($this->once())->method('listAllFilteredByTask')->with($task)
            ->will($this->returnValue($worklogs));

        $result = $this->twigExtension->taskWorklogEntries($task);

        $this->assertCount(count($worklogs), $result);
        $this->assertContainsOnlyInstancesOf('\RA\OroCrmTimeLapBundle\Model\Worklog', $result);
        $this->assertEquals($worklogs, $result);
    }

    /**
     * @test
     */
    public function taskUserTracker()
    {
        $user = new User();
        $task = new Task();

        $this->securityContext->expects($this->once())->method('getToken')->will($this->returnValue($this->token));
        $this->token->expects($this->once())->method('getUser')->will($this->returnValue($user));

        $tracker = new Tracker($user, $task, new \DateTime('now'));

        $this->trackerRepository->expects($this->once())->method('retrieveUserTracker')
            ->with($user)->will($this->returnValue($tracker));

        $result = $this->twigExtension->userTracker();

        $this->assertEquals($tracker, $result);
    }

    /**
     * @test
     */
    public function getUserUrl()
    {
        $user = new User();
        $user->setId(1);
        $url = '/user/view/4';

        $this->router->expects($this->once())
            ->method('generate')
            ->with(
                'oro_user_view',
                ['id' => $user->getId()]
            )
            ->will($this->returnValue($url));

        $this->twigExtension->getUserUrl($user);
    }
}
