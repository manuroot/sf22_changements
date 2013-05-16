<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DemandeUsergroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
            ->add('description')
           // ->add('isaccepted')
        ->add('idgroup',null,array(  'read_only' => true,'label'=>'Group'))
           // ->add('iduser')
      
        //->add('idgroup',null,array(  'disabled' => true,'label'=>'Group'))
          ->add('iduser',null,array(  'read_only' => true,'label'=>'Utilisateur'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\DemandeUsergroup'
        ));
    }

    public function getName()
    {
        return 'application_relationsbundle_demandeusergrouptype';
    }
}
