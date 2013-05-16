<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\RelationsBundle\Form\CertificatsProjetType;

class ApplisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomapplis','text',array('label' => 'Application'))
                ->add('description','text',array('label' => 'Description'));
              //  ApplisSimpleType
             //   $builder->add('tags', 'collection', array('type' => new TagType()));
       //     ->add('idprojets')
        ;
        
       /* $builder->add('idprojets', 'collection', array(
            'type' => new CertificatsProjetType(),
           'allow_add'    => true,
                 'allow_delete' => true,
                'by_reference' => false,
                  'required' => false
            
            ));*/
        
       /*  $builder->add('idprojets','entity', array(
            'class' => 'Application\RelationsBundle\Entity\CertificatsProjet',
            'expanded' => true,
            'multiple' => true,
         
            ));*/
        /* $builder->add('idprojets', 'collection', array(
    'type' => new CertificatsProjetType(),
    'prototype' => true,
    'allow_add' => true,
    'allow_delete' => true
));*/
         $builder->add('idprojets','entity', array(
            'class' => 'Application\RelationsBundle\Entity\Projet',
            'property' => 'nomprojet',
            'multiple' => true,
            'required' => true,
            'label' => 'Nom des Projets'
            ));
    }
  
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\Applis'
        ));
    }

    public function getName()
    {
        return 'Formulaire_Application';
    }
}
