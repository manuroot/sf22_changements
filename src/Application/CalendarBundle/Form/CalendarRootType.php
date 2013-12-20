<?php

namespace Application\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CalendarRootType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom')
                ->add('description','textarea')
         ->add('plage', 'choice', array(
                     'required' => true,
                    'expanded'=>false,
                    'multiple'=>false,
                    'label' => 'Plage',
                    
                    'choices' => array(
                        '15' => '15',
                        '30' => '30',
                        '60' => '60',
                    )
                ))
                ->add('startHour', 'choice', array(
                     'required' => true,
                    'expanded'=>false,
                    'multiple'=>false,
                    'label' => 'Min Heure',
                    
                    'choices' => array(
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                         '7' => '7',
                        '8' => '8',
                        '9' => '9',
                    )
                ))
                 ->add('endHour', 'choice', array(
                     'required' => true,
                    'expanded'=>false,
                    'multiple'=>false,
                    'label' => 'Max Heure',
                    
                    'choices' => array(
                        '17' => '17',
                        '18' => '18',
                        '19' => '19',
                         '20' => '20',
                        '21' => '21',
                        '22' => '22',
                        '23' => '23',
                        '24' => '24',
                    )
                ))
                
              //  ->add('groupedit',null,array( 'label' => 'Groupe Edition Admin'))
                  ->add('secondgroupedit',null,array( 'label' => 'Groupe Edition'))
                 ->add('isviewable', 'checkbox', array('label' => 'Visible Par tous','required' => false))
                ->add('isshared', 'checkbox', array('label' => 'Partager','required' => false))
                ->add('days','choice',array( 
                    'multiple'=>true,
                    'expanded'=>true,
                    'choices'=>array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','vendredi','samedi'),
                    'label' => 'Hidden Days'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CalendarBundle\Entity\CalendarRoot'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'application_calendarbundle_calendarroot';
    }

}
