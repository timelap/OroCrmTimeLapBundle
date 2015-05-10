<?php

namespace RA\OroCrmTimeLapBundle\Tests\Model;

use Oro\Bundle\UserBundle\Entity\User;
use OroCRM\Bundle\TaskBundle\Entity\Task;

use RA\OroCrmTimeLapBundle\Model\TimeSpent;
use RA\OroCrmTimeLapBundle\Model\Worklog;

class WorklogTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function thatWorklogCreatesWithEmptyDescription()
    {
        new Worklog(new TimeSpent(50), new \DateTime('now'), new Task(), new User());
    }

    /**
     * @test
     */
    public function thatWorklogCreatesWithDesscription()
    {
        new Worklog(new TimeSpent(50), new \DateTime('now'), new Task(), new User(), 'DUMMY_DESCRIPTION');
    }

    public function thatWorklogUpdates()
    {
        $worklog = new Worklog(new TimeSpent(50), new \DateTime('now'), new Task(), new User(), 'DUMMY_DESCRIPTION');

        $newTimeSpent = new TimeSpent(15);
        $newDateStarted = new \DateTime('now');
        $newDescription = 'NEW_DUMMY_DESCRIPTION';

        $worklog->update($newTimeSpent, $newDateStarted, $newDescription);

        $this->assertEquals($newTimeSpent, $worklog->getTimeSpent());
        $this->assertEquals($newDateStarted, $worklog->getDateStarted());
        $this->assertEquals($newDescription, $worklog->getDescription());
    }

    /**
     * @test
     */
    public function thatWorklogDescriptionUpdates()
    {
        $originalDescription = 'DUMMY_DESCRIPTION';
        $newDescription = 'NEW_DUMMY_DESCRIPTION';
        $worklog = new Worklog(new TimeSpent(50), new \DateTime('now'), new Task(), new User(), $originalDescription);

        $worklog->updateDescription(null);
        $this->assertEquals($originalDescription, $worklog->getDescription());

        $worklog->updateDescription('');
        $this->assertEquals('', $worklog->getDescription());

        $worklog->updateDescription($newDescription);
        $this->assertEquals($newDescription, $worklog->getDescription());
    }

    /**
     * @test
     */
    public function userFullNameRetrieves()
    {
        $firstname = 'First';
        $lastname  = 'Last';
        $user = new User();
        $user->setFirstName($firstname);
        $user->setLastName($lastname);
        $worklog = new Worklog(new TimeSpent(50), new \DateTime('now'), new Task(), $user, 'DUMMY_DESCRIPTION');

        $this->assertEquals(sprintf('%s %s', $firstname, $lastname), $worklog->getUserFullName());
    }
}
