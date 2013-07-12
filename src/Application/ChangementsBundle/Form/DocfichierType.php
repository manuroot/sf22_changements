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
               'required'=>true,
               )
               )
                /* ->add('OriginalFilename','text',array(
                     'attr' => array('style' => 'width:150px'),
               'label' => 'Nom',
               'required'=>false,
                     'read_only'=>true,
                     //'disabled'=>true,
               )
               )*/
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