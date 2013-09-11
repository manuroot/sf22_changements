<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Application\CertificatsBundle\Form\EventListener\AddFichierFieldSubscriber;

class CertificatsFilesAddType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        /* $builder->add('file', new CertificatsFilesType(),array(
          'label'=>'Fichier',
          'required'=>false))

         */
        /*  $builder->addEventSubscriber(new AddFichierFieldSubscriber()) */
        // $builder->add('file','file',array('label'=>'Fichier (*)','required'=>true,))
        $builder->addEventSubscriber(new AddFichierFieldSubscriber());

        $builder
                //  ->add('file',null,array('label' => 'MAJ Fichier'))
                ->add('name', null, array('label' => 'Nom'))
                
                
                
                ->add('typeCert', 'entity', array(
                    'class' => 'Application\CertificatsBundle\Entity\CertificatsFiletype',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.fileType', 'ASC');
                    },
                    'property' => 'FileType',
                    'multiple' => false,
                    'required' => false,
                    'label' => 'Type',
                    'mapped' => false,
                    'empty_value' => '--- Choisir une option ---'
                ))
                ->add('creer_demande', 'checkbox', array(
                    'attr' => array('checked' => 'checked'),
                    'label' => "Créer automatiquement une entrée",
                    'mapped' => false,
                    'required'=>false
                ))
                ->add('certificats', 'entity', array(
                    //'class' => 'Application\CertificatsBundle\Entity\CertificatsProjet',
                    'class' => 'ApplicationCertificatsBundle:CertificatsCenter',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.fileName', 'ASC');
                    },
                    'property' => 'fileName',
                    'multiple' => false,
                    'required' => false,
                    'label' => 'Certificat',
                    'empty_value' => '--- Choisir une option ---'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            //    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'data_class' => 'Application\CertificatsBundle\Entity\CertificatsFiles',
            'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'fichier_certificat';
    }

}