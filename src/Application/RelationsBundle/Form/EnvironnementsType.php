<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnvironnementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add('nom', null, array(
                    'label'=>'Nom',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )))
                
                 ->add('description', null, array(
                    'label'=>'Description',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ))) 
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\Environnements'
        ));
    }

    public function getName()
    {
        return 'application_certificatsbundle_environnementstype';
    }
}
