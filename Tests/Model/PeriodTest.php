<?php

namespace RA\OroCrmTimeLapBundle\Tests\Model;

use RA\OroCrmTimeLapBundle\Model\Period;

class PeriodTest extends \PHPUnit_Framework_TestCase
{
    public function testCreates()
    {
        $begin = new \DateTime('2014-12-01');
        $end = new \DateTime('2014-12-31');
        $period = new Period($begin, $end);

        $this->assertEquals($begin, $period->getBegin());
        $this->assertEquals($end, $period->getEnd());

        $begin->setDate(2013, 12, 01);
        $end->setDate(2013, 12, 31);

        $this->assertEquals('2014-12-01', $period->getBegin()->format('Y-m-d'));
        $this->assertEquals('2014-12-31', $period->getEnd()->format('Y-m-d'));

        $period->getBegin()->setDate(2012, 12, 01);
        $period->getEnd()->setDate(2012, 12, 31);

        $this->assertEquals('2014-12-01', $period->getBegin()->format('Y-m-d'));
        $this->assertEquals('2014-12-31', $period->getEnd()->format('Y-m-d'));
    }
}
