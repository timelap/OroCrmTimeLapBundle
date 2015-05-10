<?php

namespace RA\OroCrmTimeLapBundle\Model;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

class Worklog
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var TimeSpent
     */
    protected $timeSpent;

    /**
     * @var \DateTime
     */
    protected $dateStarted;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \OroCRM\Bundle\TaskBundle\Entity\Task
     */
    protected $task;

    /**
     * @var \Oro\Bundle\UserBundle\Entity\User
     */
    protected $user;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @param TimeSpent $timeSpent
     * @param \DateTime $dateStarted
     * @param Task $task
     * @param User $user
     * @param string|null $description
     */
    public function __construct(
        TimeSpent $timeSpent,
        \DateTime $dateStarted,
        Task $task,
        User $user,
        $description = null
    ) {
        $this->timeSpent = $timeSpent;
        $this->dateStarted = clone $dateStarted;
        $this->task = $task;
        $this->user = $user;
        $this->description = $description;

        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = clone $this->createdAt;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return TimeSpent
     */
    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    /**
     * @return \DateTime
     */
    public function getDateStarted()
    {
        return $this->dateStarted;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \OroCRM\Bundle\TaskBundle\Entity\Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return \Oro\Bundle\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getUserFullName()
    {
        return sprintf('%s %s', $this->user->getFirstName(), $this->user->getLastName());
    }

    /**
     * @param TimeSpent $timeSpent
     * @param \DateTime $dateStarted
     * @param null|string $description
     * @return void
     */
    public function update(TimeSpent $timeSpent, \DateTime $dateStarted, $description = null)
    {
        $this->timeSpent = $timeSpent;
        $this->dateStarted = clone $dateStarted;
        if (null !== $description) {
            $this->updateDescription((string) $description);
        }
    }

    /**
     * @param string $newDescription
     */
    public function updateDescription($newDescription)
    {
        if (null === $newDescription) {
            return;
        }
        $this->description = (string) $newDescription;
    }
}
