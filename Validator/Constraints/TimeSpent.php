<?php

namespace RA\OroCrmTimeLapBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TimeSpent extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value is not a valid time spent format.';
}
