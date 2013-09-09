<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CertificatsFilesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('file', 'file', array('label' => 'Uploader Fichier'))
               /* ->add('creer_demande', 'checkbox', array(
                    'attr' => array('checked' => 'checked'),
        ))*/
                ;
        //  ->add('name')
        //  ->add('name','text',array('label' => 'Description du Fichier'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            //    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'data_class' => 'Application\CertificatsBundle\Entity\CertificatsFiles',
            'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'certificats_fichier';
    }

}