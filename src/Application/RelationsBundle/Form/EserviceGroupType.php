<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EserviceGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder->add('nomGroup', 'text', array(
                    'widget_addon' => array(
                        'label'=> 'Nom du groupe',
                        'icon' => 'user',
                        'type' => 'prepend'
                    ),
                ))
                ->add('description', 'text', array(
                    'widget_addon' => array(
                        'label'=> 'Description',
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                ))
                  ->add('email', 'text', array(
                    'widget_addon' => array(
                        'label'=> 'envelope',
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                ));
       
       /* $builder
         //  ->add('nomGroup',null,array('label'=> 'Nom du groupe'))
            ->add('description',null,array('label'=> 'Description'))
            ->add('email')
        ;*/
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\EserviceGroup'
        ));
    }

    public function getName()
    {
        return 'application_eservicesbundle_eservicegrouptype';
    }
}
