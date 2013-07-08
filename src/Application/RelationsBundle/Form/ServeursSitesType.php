<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServeursSitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,array('label'=>'Nom du Site'))
             ->add('description',null,array('label'=>'Description'))
             ->add('ip',null,array('label'=>'Ip'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\ServeursSites'
        ));
    }

    public function getName()
    {
        return 'application_relationsbundle_serveurssitestype';
    }
}
