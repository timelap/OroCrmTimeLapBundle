<?php

namespace RA\OroCrmTimeLapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="RA\OroCrmTimeLapBundle\Infrastructure\Persistence\DoctrineTrackerRepository")
 * @ORM\Table(name="timelap_tracker")
 */
class Tracker extends \RA\OroCrmTimeLapBundle\Model\Tracker
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Oro\Bundle\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var \OroCRM\Bundle\TaskBundle\Entity\Task
     *
     * @ORM\ManyToOne(targetEntity="\OroCRM\Bundle\TaskBundle\Entity\Task")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $task;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_started", type="datetime")
     */
    protected $dateStarted;
}
