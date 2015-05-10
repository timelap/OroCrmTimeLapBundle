<?php

namespace RA\OroCrmTimeLapBundle\Model;

interface PeriodInterface
{
    /**
     * @return \DateTime
     */
    public function getBegin();

    /**
     * @return \DateTime
     */
    public function getEnd();
}
