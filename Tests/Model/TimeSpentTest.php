<?php

namespace RA\OroCrmTimeLapBundle\Tests\Model;

use RA\OroCrmTimeLapBundle\Model\TimeSpent;

class TimeSpentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidInputValues
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid given value of seconds.
     * @param $value
     */
    public function testThatThrowsExceptionWhenCreates($value)
    {
        new TimeSpent($value);
    }

    /**
     * @return array
     */
    public function invalidInputValues()
    {
        return [
            ['123'],
            [[]],
            [new TimeSpent(1)],
            [123.4]
        ];
    }

    public function testThatCreates()
    {
        $timeSpent = new TimeSpent(123);
        $this->assertEquals(123, $timeSpent->getValue());
    }

    public function testFormat()
    {
        $timeSpent = new TimeSpent(9900);
        $this->assertEquals('2h 45m', $timeSpent->format());

        $timeSpent = new TimeSpent(178440);
        $this->assertEquals('2d 1h 34m', (string) $timeSpent);
    }
}
