<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApplisType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nomapplis', null, array(
                    'label' => 'Nom',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
            )))
                ->add('description', 'textarea', array(
                    'label' => 'Description',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
            )))
        ;


        $builder->add('idprojets', 'entity', array(
            'class' => 'Application\RelationsBundle\Entity\Projet',
            'property' => 'nomprojet',
            'multiple' => true,
            'required' => true,
            'label' => 'Nom des Projets',
            'by_reference' => false
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\Applis'
        ));
    }

    public function getName() {
        return 'Formulaire_Application';
    }

}
