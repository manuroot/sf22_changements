<?php

namespace Application\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CalendarGroupType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomGroup')
            ->add('description')
            ->add('email')
            ->add('users')
            ->add('owner')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CalendarBundle\Entity\CalendarGroup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'application_calendarbundle_calendargroup';
    }
}
