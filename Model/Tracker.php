<?php

namespace RA\OroCrmTimeLapBundle\Model;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

class Tracker
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Task
     */
    protected $task;

    /**
     * @var \DateTime
     */
    protected $dateStarted;

    /**
     * @param User $user
     * @param Task $task
     */
    public function __construct(User $user, Task $task)
    {
        $this->user = $user;
        $this->task = $task;
        $this->dateStarted = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return \DateTime
     */
    public function getDateStarted()
    {
        return clone $this->dateStarted;
    }

    /**
     * Retrieves difference between start and current datetime points in seconds
     * @return int
     */
    public function getSpentSeconds()
    {
        $diff = $this->dateStarted->diff(new \DateTime('now', new \DateTimeZone('UTC')));
        $seconds = ($diff->y * TimeSpent::YEAR)
            + ($diff->m * TimeSpent::MONTH)
            + ($diff->d * TimeSpent::DAY)
            + ($diff->h * TimeSpent::HOUR)
            + ($diff->i * TimeSpent::MINUTE)
            + $diff->s;

        return $seconds;
    }
}
