<?php

namespace RA\OroCrmTimeLapBundle\Tests\Model;

use RA\OroCrmTimeLapBundle\Model\TimeSpentFactory;

class TimeSpentFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TimeSpentFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new TimeSpentFactory();
    }

    public function testCreates()
    {
        $seconds = 123;
        $this->factory->create($seconds);
    }

    /**
     * @dataProvider invalidInputFormat
     * @expectedException \RA\OroCrmTimeLapBundle\Model\TimeSpentInvalidFormatException
     * @expectedExceptionMessage Input format is invalid.
     * @param string $input
     */
    public function testCreatesFromStringThrowsException($input)
    {
        $this->factory->createFromString($input);
    }

    /**
     * @return array
     */
    public function invalidInputFormat()
    {
        return [
            ['2hh 45m'],
            ['2d 1h 34mm'],
            ['3dd']
        ];
    }

    /**
     * @dataProvider getTimeSpentStrings
     * @param string $input
     * @param int $expectedSeconds
     */
    public function testCreatesFromString($input, $expectedSeconds)
    {
        $timeSpent = $this->factory->createFromString($input);
        $this->assertEquals($expectedSeconds, $timeSpent->getValue());
    }

    /**
     * @return array
     */
    public function getTimeSpentStrings()
    {
        return [
            ['2h 45m', 9900],
            ['2d 1h 34m', 178440],
            ['4h', 14400]
        ];
    }
}
