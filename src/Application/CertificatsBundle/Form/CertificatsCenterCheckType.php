<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Application\CertificatsBundle\Model\MyOpenSsl;

class CertificatsCenterCheckType extends AbstractType {

    private $operations;

    public function __construct() {
        $obj_openssl = new MyOpenSsl();

        $this->operations = $obj_openssl->getOperations();
        $this->fichiers = $obj_openssl->getFichiers();
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        /* ->add('contenu', 'textarea', array(
          //    'attr' => array('rows' => 20,'cols' => 20)
         */
        $builder->add('contenu', 'textarea', array(
        'attr' => array(
        'placeholder' => 'Placer le Contenu de votre certificat ICI',
        'help_block' => 'Example block-level help text here.'
        )))
        ->add('opecert', 'choice', array(
        'widget_addon' => array(
        'icon' => 'time',
        'type' => 'prepend'
        ),
       'choices' => $this->fichiers,
        'label' => 'Certificat',
        'multiple' => false,
       
        ))
                
       ->add('fichier', 'entity', array(
            'class' => 'Application\CertificatsBundle\Entity\CertificatsFiles',
            'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('a')
                                ->select('a,b,c')
                         ->leftJoin('a.certificats', 'b')
                          ->leftJoin('b.typeCert', 'c')
                          ->andwhere('c.fileType LIKE :mytype')
                          ->setParameter('mytype', '%CERT%')
                          ->orderBy('a.OriginalFilename', 'ASC');
            },
            'property' => 'OriginalFilename',
            'multiple' => false,
            'required' => true,
            'label' => 'Fichier',
            'empty_value' => '--- Choisir une option ---'
        ))
         
     ->add('fichierp12', 'entity', array(
            'class' => 'Application\CertificatsBundle\Entity\CertificatsFiles',
            'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('a')
                                ->select('a,b,c')
                         ->leftJoin('a.certificats', 'b')
                          ->leftJoin('b.typeCert', 'c')
                          ->andwhere('c.fileType LIKE :mytype')
                          ->setParameter('mytype', '%P12%')
                          ->orderBy('a.OriginalFilename', 'ASC');
            },
            'property' => 'OriginalFilename',
            'multiple' => false,
            'required' => true,
            'label' => 'Fichierp12',
            'empty_value' => '--- Choisir une option ---'
        ))
        
                     ->add('fichiercrl', 'entity', array(
            'class' => 'Application\CertificatsBundle\Entity\CertificatsFiles',
            'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('a')
                                ->select('a,b,c')
                         ->leftJoin('a.certificats', 'b')
                          ->leftJoin('b.typeCert', 'c')
                          ->andwhere('c.fileType LIKE :mytype')
                          ->setParameter('mytype', '%CRL%')
                          ->orderBy('a.OriginalFilename', 'ASC');
            },
            'property' => 'OriginalFilename',
            'multiple' => false,
            'required' => true,
            'label' => 'Fichier crl',
            'empty_value' => '--- Choisir une option ---'
        ))
        ->add('typecert', 'choice', array('label' => 'Opération',
            'multiple' => false,
            'choices' => $this->operations,
                //   'attr' => array('style' => 'width:100px', 'customattr' => 'customdata')
        ));

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
                //'data_class' => 'Application\CertificatsBundle\Entity\CertificatsCenter'
        ));
    }

    public function getName() {
        return 'checkcert';
    }

}
