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
                        '15' => '15mn',
                        '30' => '30mn',
                        '60' => '60mn',
                    )
                ))
                ->add('startHour', 'choice', array(
                     'required' => true,
                    'expanded'=>false,
                    'multiple'=>false,
                    'label' => 'Min Heure',
                    
                    'choices' => array(
                        '1'=>'1h',
                        '2'=>'2h',
                        '3'=>'3h',
                        '4' => '4h',
                        '5' => '5h',
                        '6' => '6h',
                         '7' => '7h',
                        '8' => '8h',
                        '9' => '9h',
                    )
                ))
                 ->add('endHour', 'choice', array(
                     'required' => true,
                    'expanded'=>false,
                    'multiple'=>false,
                    'label' => 'Max Heure',
                    
                    'choices' => array(
                        '17' => '17h',
                        '18' => '18h',
                        '19' => '19h',
                         '20' => '20h',
                        '21' => '21h',
                        '22' => '22h',
                        '23' => '23h',
                        '24' => '24h',
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
