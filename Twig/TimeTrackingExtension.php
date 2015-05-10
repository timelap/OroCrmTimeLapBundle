<?php

namespace RA\OroCrmTimeLapBundle\Twig;

use Doctrine\Common\Collections\Collection;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Oro\Bundle\AttachmentBundle\Manager\AttachmentManager;
use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\TotalTimeSpentCalculator;
use RA\OroCrmTimeLapBundle\Model\WorklogRepository;
use RA\OroCrmTimeLapBundle\Model\TrackerRepository;
use RA\OroCrmTimeLapBundle\Model\Worklog;
use RA\OroCrmTimeLapBundle\Model\Tracker;

class TimeTrackingExtension extends \Twig_Extension
{
    /**
     * @var TotalTimeSpentCalculator
     */
    private $calculator;

    /**
     * @var WorklogRepository
     */
    private $worklogRepository;

    /**
     * @var TrackerRepository
     */
    private $trackerRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AttachmentManager
     */
    private $attachmentManager;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @param TotalTimeSpentCalculator $calculator
     * @param WorklogRepository        $worklogRepository
     * @param TrackerRepository        $trackerRepository
     * @param RouterInterface          $router
     * @param AttachmentManager        $attachmentManager
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(
        TotalTimeSpentCalculator $calculator,
        WorklogRepository $worklogRepository,
        TrackerRepository $trackerRepository,
        RouterInterface $router,
        AttachmentManager $attachmentManager,
        SecurityContextInterface $securityContext
    ) {
        $this->calculator        = $calculator;
        $this->worklogRepository = $worklogRepository;
        $this->trackerRepository = $trackerRepository;
        $this->router            = $router;
        $this->attachmentManager = $attachmentManager;
        $this->securityContext   = $securityContext;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'timelap';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'timelap_task_worklog_total_timespent',
                [$this, 'taskWorklogTotalTimeSpent']
            ),
            new \Twig_SimpleFunction(
                'timelap_task_worklog_entries',
                [$this, 'taskWorklogEntries']
            ),
            new \Twig_SimpleFunction(
                'timelap_user_tracker',
                [$this, 'userTracker']
            ),
            new \Twig_SimpleFunction(
                'timelap_get_user_url',
                [$this, 'getUserUrl']
            ),
            new \Twig_SimpleFunction(
                'timelap_get_user_avatar',
                [$this, 'getUserAvatar']
            )
        ];
    }

    /**
     * @param Task $task
     * @return string
     */
    public function taskWorklogTotalTimeSpent(Task $task)
    {
        $timeSpent = $this->calculator->calculatePerTask($task);
        return (string) $timeSpent;
    }

    /**
     * @param Task $task
     * @return Collection|Worklog[]
     */
    public function taskWorklogEntries(Task $task)
    {
        return $this->worklogRepository->listAllFilteredByTask($task);
    }

    /**
     * @return Tracker
     */
    public function userTracker()
    {
        $token = $this->securityContext->getToken();
        $user = $token ? $token->getUser() : null;

        return $this->trackerRepository->retrieveUserTracker($user);
    }

    /**
     * @param User $user
     * @return string
     */
    public function getUserUrl(User $user)
    {
        return $this->router->generate('oro_user_view', ['id' => $user->getId()]);
    }

    /**
     * @param User $user
     * @return null|string
     */
    public function getUserAvatar(User $user)
    {
        return
            $user->getAvatar()
                ? $this->attachmentManager->getFilteredImageUrl($user->getAvatar(), 'avatar_xsmall') : null;
    }
}
