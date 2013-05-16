<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChronoUsergroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomGroup')
                ->add('description')
                  ->add('email')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\ChronoUsergroup'
        ));
    }

    public function getName()
    {
        return 'application_groupe';
    }
}
