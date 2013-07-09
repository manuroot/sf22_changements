<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServeursType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom',null,array(
                    'label'=>'Nom du Serveur',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                     )))
                ->add('description',null,array(
                    'attr' => array(
                           'placeholder' => 'ex: 12345 (5 a 10 car.)'
                           ),
                    'label'=>'Description',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                 )))
                ->add('ip_in',null,array(
                    'attr' => array(
                           'placeholder' => 'ex: 192.168.1.12'
                           ),
                    'label'=>'IP_in',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )
                    ))
                 ->add('ip_out',null,array(
                      'attr' => array(
                           'placeholder' => 'ex: 192.168.1.12'
                           ),
                    'label'=>'IP_out',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )
                    ))    
            
               
                ->add('nom_dns',null,array('label'=>'Nom DNS',
                     'attr' => array(
                           'placeholder' => 'Nom dns de reference'
                           ),
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )))
                ->add('idzone', null, array('label' => 'Zone',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )))
                ->add('idsite', null, array('label' => 'Site',
                     'attr' => array(
                           'placeholder' => 'Zone de reference'
                           ),
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )))
                ->add('id_env', null, array('label' => 'Env',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )))
                        ->add('warning', 'checkbox', array('label' => 'Activer le Warning','required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\Serveurs'
        ));
    }

    public function getName() {
        return 'application_relationsbundle_serveurstype';
    }

}
