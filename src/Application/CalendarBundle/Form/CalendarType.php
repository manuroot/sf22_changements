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
                ->add('startDatetime','hidden')
                ->add('endDatetime','hidden')
                ->add('description', 'textarea')
                ->add('cssClass','hidden')

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
