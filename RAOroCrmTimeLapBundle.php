<?php

namespace RA\OroCrmTimeLapBundle;

use Doctrine\DBAL\Types\Type;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RAOroCrmTimeLapBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (!Type::hasType('time_spent')) {
            Type::addType(
                'time_spent',
                'RA\OroCrmTimeLapBundle\Infrastructure\Persistence\Doctrine\DBAL\Types\TimeSpentType'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
