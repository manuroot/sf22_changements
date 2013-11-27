<?php

namespace Application\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CalendarEvenementsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
           ->add('description','textarea')
            ->add('cssClass')
              /*  ->add('rootcalendar', null, array(
                    'class' => 'ApplicationCalendarBundle:CalendarRoot',
                    'property' => 'nom',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                 // 'disabled' => false,
                   // 'mapped' => false,
                 //   '//read_only'=>true,
                   
                    'label' => 'Calendrier'
                 //   'empty_value' => '--- Choisir une option ---'
                ))*/
            
                  
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CalendarBundle\Entity\CalendarEvenements'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'application_calendarbundle_calendarevenements';
    }
}
