<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ChangementsFilesForEntityType extends AbstractType {
// $form->add('file','file',array('label'=>'Fichier (*)','required'=>true,));
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->setAttribute('show_legend', false); // no legend for main form
        $builder->add('picture', 'collection', array(
                    'type' => new ChangementDocumentsType(),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                       'prototype' => true,
                      'label'=>false,
              
                    ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
       'data_class' => 'Application\ChangementsBundle\Entity\Changements',
            'cascade_validation' => true,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'task_item',
        ));
    }

    public function getName() {
        return 'fileschangements';
    }

}
