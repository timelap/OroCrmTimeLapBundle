<?php

namespace RA\OroCrmTimeLapBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorklogType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taskId', 'hidden')
            ->add('timeSpent', 'text', [
                'required' => true,
                'tooltip'  => '1d 3h 45m',
                'label' => 'Time Spent:'
            ])
            ->add('dateStarted', 'oro_datetime', [
                'data' => new \DateTime('now', new \DateTimeZone('UTC')),
                'required' => true,
                'label' => 'Date Started'
            ])
            ->add('description', 'textarea', [
                'required' => false,
                'label' => 'Description'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'RA\OroCrmTimeLapBundle\Form\WorklogInput',
                'intention' => 'worklogEntry',
                'cascade_validation' => true
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'timelap_worklog_form';
    }
}
