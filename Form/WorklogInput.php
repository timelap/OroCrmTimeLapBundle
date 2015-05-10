<?php

namespace RA\OroCrmTimeLapBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;

use RA\OroCrmTimeLapBundle\Validator\Constraints as CustomAssert;

class WorklogInput
{
    /**
     * @var int
     */
    private $id;

    /**
     * @CustomAssert\TimeSpent()
     */
    private $timeSpent;

    /**
     * @Assert\DateTime()
     * @var \DateTime
     */
    private $dateStarted;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $taskId;

    /**
     * @var int
     */
    private $userId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTimeSpent()
    {
        return $this->timeSpent;
    }

    /**
     * @param int $timeSpent
     */
    public function setTimeSpent($timeSpent)
    {
        $this->timeSpent = $timeSpent;
    }

    /**
     * @return \DateTime
     */
    public function getDateStarted()
    {
        return $this->dateStarted;
    }

    /**
     * @param \DateTime $dateStarted
     */
    public function setDateStarted($dateStarted)
    {
        $this->dateStarted = $dateStarted;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * @param mixed $taskId
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}
