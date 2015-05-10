<?php

namespace RA\OroCrmTimeLapBundle\Tests\Infrastructure\Persistence;

use Eltrino\PHPUnit\MockAnnotations\MockAnnotations;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\BasicEntityPersister;
use Doctrine\ORM\UnitOfWork;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Infrastructure\Persistence\DoctrineTrackerRepository;
use RA\OroCrmTimeLapBundle\Model\Tracker;

class DoctrineTimeTrackingRecordRepositoryTest extends \PHPUnit_Framework_TestCase
{
    const CLASS_NAME = 'DUMMY_CLASS_NAME';

    /**
     * @var DoctrineTrackerRepository
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
        $this->repository = new DoctrineTrackerRepository($this->em, $this->classMetadata);
    }

    public function testRetrieveUserTracker()
    {
        $user = new User();
        $tracker = $this->tracker();

        $this->em->expects($this->once())->method('getUnitOfWork')->will($this->returnValue($this->unitOfWork));

        $this->unitOfWork->expects($this->once())->method('getEntityPersister')->with(self::CLASS_NAME)
            ->will($this->returnValue($this->entityPersister));

        $this->entityPersister->expects($this->once())->method('load')
            ->with(['user' => $user], null, null, [], 0, 1, null)
            ->will($this->returnValue($tracker));

        $this->repository->retrieveUserTracker($user);
    }

    public function testSave()
    {
        $tracker = $this->tracker();
        $this->em->expects($this->once())->method('persist')->with($tracker);
        $this->em->expects($this->once())->method('flush');
        $this->repository->save($tracker);
    }

    public function testRemoveTracker()
    {
        $tracker = $this->tracker();
        $this->em->expects($this->once())->method('remove')->with($tracker);
        $this->em->expects($this->once())->method('flush');
        $this->repository->removeTracker($tracker);
    }

    /**
     * @return Tracker
     */
    private function tracker()
    {
        $user = new User();
        $task = new Task();
        return new Tracker($user, $task, new \DateTime('now'));
    }
}
