<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServeursZonesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                  ->add('nom',null,array('label'=>'Nom de la Zone'))
             ->add('description',null,array('label'=>'Description'))
        
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\ServeursZones'
        ));
    }

    public function getName()
    {
        return 'application_relationsbundle_serveurszonestype';
    }
}
