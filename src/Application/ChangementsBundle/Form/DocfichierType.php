<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DocfichierType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('file')
               // ->add('name')
           ->add('name','text',array(
               'label' => 'Description du Fichier',
               'required'=>false,
               )
               )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            //    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'data_class' => 'Application\ChangementsBundle\Entity\Docchangements',
                // 'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'doc';
    }

}