<?php

namespace Application\CalendarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CalendarType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * 
     * 
     * 
     * 
     * 
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title','text',array('label'=>'Titre'))
                ->add('bgColor','text',array('label'=>'Background Color'))
                ->add('fgColor','text',array('label'=>'Font Color'))
               // ->add('startDatetime','hidden')
              //  ->add('endDatetime','hidden')
                ->add('description', 'textarea')
                ->add('cssClass','hidden')
                    ->add('endDatetime', 'datetime', array(
                    'label' => 'Date Fin',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                ))
                ->add('startDatetime', 'datetime', array(
                    'label' => 'Date Debut',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                    'required' => false,
                ))

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
         //   'data_class' => 'Application\CalendarBundle\Entity\AdesignCalendar'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return ;
    }

}
