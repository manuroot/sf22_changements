<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChronoUsergroupType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nomGroup', null, array(
                    'attr' => array(
                        'placeholder' => "nom d'utlisateur",
                    ),
                    'label' => 'Nom du groupe',
                    'widget_addon' => array(
                        'icon' => 'user',
                        'type' => 'prepend'
            )))
                ->add('description', null, array(
                    'label' => 'Description',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
            )))
                ->add('email', null, array(
                    'label' => 'Email',
                    'attr' => array(
                        'placeholder' => "username@domaine",
                    ),
                    'widget_addon' => array(
                        'icon' => 'envelope',
                        'type' => 'prepend'
        )));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\ChronoUsergroup'
        ));
    }

    public function getName() {
        return 'application_groupe';
    }

}
