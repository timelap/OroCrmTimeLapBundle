<?php

namespace RA\OroCrmTimeLapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="RA\OroCrmTimeLapBundle\Infrastructure\Persistence\DoctrineWorklogRepository")
 * @ORM\Table(name="timelap_worklog")
 * )
 */
class Worklog extends \RA\OroCrmTimeLapBundle\Model\Worklog
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
     * @var int
     *
     * @ORM\Column(name="time_spent", type="time_spent")
     */
    protected $timeSpent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_started", type="datetime")
     */
    protected $dateStarted;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var \OroCRM\Bundle\TaskBundle\Entity\Task
     *
     * @ORM\ManyToOne(targetEntity="\OroCRM\Bundle\TaskBundle\Entity\Task")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $task;

    /**
     * @var \Oro\Bundle\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;
}
