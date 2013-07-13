<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

//use Application\CertificatsBuundle\Form\CertificatsTagType;

class ProjetType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nomprojet', null, array(
                    'label' => 'Nom',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
            )))
                ->add('description', 'textarea', array(
                    'label' => 'Description',
        ));


        $builder->add('idapplis', 'entity', array(
                    'class' => 'Application\RelationsBundle\Entity\Applis',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nomapplis', 'ASC');
                    },
                    'property' => 'nomapplis',
                    'multiple' => true,
                    'required' => true,
                    'label' => 'Applications'
                ))
                ->add('picture', 'collection', array('type' => new ProjetDocumentsType,
                    'allow_add' => true,
                    'by_reference' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'prototype_name' => 'doc__name__',));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\Projet',
             'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'Formulaire_Projet';
    }

}
