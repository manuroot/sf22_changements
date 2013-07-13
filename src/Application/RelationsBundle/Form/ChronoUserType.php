<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChronoUserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nomUser', null, array(
                    'attr' => array(
                        'placeholder' => "nom d'utlisateur",
                    ),
                    'label' => 'Nom Utilisateur',
                    'widget_addon' => array(
                        'icon' => 'user',
                        'type' => 'prepend'
            )))
                ->add('idgroup', 'entity', array(
                    //'class' => 'Application\RelationsBundle\Entity\CertificatsProjet',
                    'class' => 'ApplicationRelationsBundle:ChronoUsergroup',
                    'property' => 'nomGroup',
                    'multiple' => false,
                    'required' => true,
                    'label' => 'Groupe',
                    'empty_value' => '--- Choisir une option ---'
                ))
                ->add('infos', null, array(
                    'label' => 'Infos',
                    'attr' => array(
                        'placeholder' => "ex: nom réel",
                    ),
                    'widget_addon' => array(
                        'icon' => 'question-sign',
                        'type' => 'prepend'
            )))
                ->add('telephone', null, array(
                    'label' => 'Telephone',
                    'attr' => array(
                        'placeholder' => "xx-xx-xx-xx",
                    ),
                    'widget_addon' => array(
                        'icon' => 'headphones',
                        'type' => 'prepend'
            )))
                ->add('bureau')
                ->add('bureau', null, array(
                    'attr' => array(
                        'placeholder' => "numéro du bureau",
                    ),
                    'label' => 'Bureau',
                    'widget_addon' => array(
                        'icon' => 'briefcase',
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
            )))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\ChronoUser'
        ));
    }

    public function getName() {
        return 'application_user';
    }

}
