<?php

namespace RA\OroCrmTimeLapBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use RA\OroCrmTimeLapBundle\Model\TimeSpent as TimeSpentObject;

class TimeSpentValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var TimeSpent $constraint */
        if (false === TimeSpentObject::isValid($value)) {
            $this->context->addViolation(
                $constraint->message,
                ['%string%' => $value]
            );
        }
    }
}
