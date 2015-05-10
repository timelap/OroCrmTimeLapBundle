<?php

namespace RA\OroCrmTimeLapBundle\Tests\Infrastructure\Persistence;

use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\BasicEntityPersister;
use Doctrine\ORM\UnitOfWork;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Infrastructure\Persistence\DoctrineWorklogRepository;
use RA\OroCrmTimeLapBundle\Model\Period;
use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\Worklog;

class DoctrineWorklogRepositoryTest extends \PHPUnit_Framework_TestCase
{
    const CLASS_NAME = 'DUMMY_CLASS_NAME';

    /**
     * @var DoctrineWorklogRepository
     */
    private $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EntityManager
     * @Mock \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ClassMetadata
     * @Mock \Doctrine\ORM\Mapping\ClassMetadata
     */
    private $classMetadata;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|UnitOfWork
     * @Mock \Doctrine\ORM\UnitOfWork
     */
    private $unitOfWork;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|BasicEntityPersister
     * @Mock \Doctrine\ORM\Persisters\BasicEntityPersister
     */
    private $entityPersister;

    protected function setUp()
    {
        MockAnnotations::init($this);
        $this->classMetadata->name = self::CLASS_NAME;
        $this->repository = new DoctrineWorklogRepository($this->em, $this->classMetadata);
    }

    /**
     * @test
     */
    public function save()
    {
        $worklog = $this->worklog();
        $this->em->expects($this->once())->method('persist')->with($worklog);
        $this->em->expects($this->once())->method('flush');
        $this->repository->save($worklog);
    }

    /**
     * @test
     */
    public function get()
    {
        $id = 1;
        $worklog = $this->worklog();

        $this->em->expects($this->once())->method('find')
            ->with(self::CLASS_NAME, $id, 0, null)
            ->will($this->returnValue($worklog));

        $result = $this->repository->get($id);

        $this->assertEquals($worklog, $result);
    }

    /**
     * @test
     */
    public function delete()
    {
        $worklog = $this->worklog();
        $this->em->expects($this->once())->method('remove')->with($worklog);
        $this->em->expects($this->once())->method('flush');
        $this->repository->delete($worklog);
    }

    /**
     * @test
     */
    public function testListAllFilteredByTask()
    {
        $taskId = 1;
        $task = new Task();
        $task->setId($taskId);
        $worklogs = [$this->worklog()];

        $this->em->expects($this->once())->method('getUnitOfWork')->will($this->returnValue($this->unitOfWork));

        $this->unitOfWork->expects($this->once())->method('getEntityPersister')->with(self::CLASS_NAME)
            ->will($this->returnValue($this->entityPersister));

        $this->entityPersister->expects($this->once())->method('loadAll')
            ->with(['task' => $taskId], null, null, null)
            ->will($this->returnValue($worklogs));

        $result = $this->repository->listAllFilteredByTask($task);

        $this->assertTrue(is_array($result));
        $this->assertEquals($worklogs, $result);
    }

    public function testListAllByUserAndPeriod()
    {
        $user = new User();
        $period = new Period(new \DateTime('2014-12-01'), new \DateTime('2014-12-31'));
        $worklogs = [$this->worklog()];

        $this->em->expects($this->once())->method('getUnitOfWork')->will($this->returnValue($this->unitOfWork));

        $this->unitOfWork->expects($this->once())->method('getEntityPersister')->with(self::CLASS_NAME)
            ->will($this->returnValue($this->entityPersister));

        $this->entityPersister->expects($this->once())->method('loadCriteria')
            ->with(
                $this->logicalAnd(
                    $this->isInstanceOf('\Doctrine\Common\Collections\Criteria')
                )
            )
            ->will($this->returnValue($worklogs));

        $result = $this->repository->listAllByUserAndPeriod($user, $period);

        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $result);
        $this->assertEquals($result->toArray(), $worklogs);
    }

    /**
     * @return Worklog
     */
    private function worklog()
    {
        $task = new Task();
        $user = new User();
        return new Worklog(new TimeSpent(123), new \DateTime('now'), $task, $user);
    }
}
