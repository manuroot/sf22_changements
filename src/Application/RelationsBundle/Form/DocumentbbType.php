<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentbbType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('path')
            ->add('idprojets')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\Documentbb'
        ));
    }

    public function getName()
    {
        return 'application_relationsbundle_documentbbtype';
    }
}
