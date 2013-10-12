<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangementsContactType extends AbstractType {

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
            'data_class' => 'Application\ChangementsBundle\Entity\ChangementsContact'
        ));
    }

    public function getName() {
        return 'application_changements_contact';
    }

}
