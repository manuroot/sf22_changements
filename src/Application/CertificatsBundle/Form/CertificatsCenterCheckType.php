<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Application\CertificatsBundle\Model\MyOpenSsl;

class CertificatsCenterCheckType extends AbstractType {

    private $operations_check;
    private $operations_view;
    private $fichiers;
    private $type_fichiers;
    private $type_password;

    public function __construct() {
        $obj_openssl = new MyOpenSsl();
        list($this->operations_view, $this->operations_check) = $obj_openssl->getOperations();

        $this->type_fichiers = $obj_openssl->getFichiers();
        $this->type_password = $obj_openssl->getPassword();
        $this->type_password = array(
            'password_key_cert' => 'Password Cert Key',
            'password_key_ac' => 'Password AC_Cert Key',
            'password_p12' => 'Password P12 ',
            'password_contenu' => 'Password Contenu ',
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('contenu', 'textarea', array(
            'mapped'=>false,
                    'attr' => array(
                        'placeholder' => 'Placer le Contenu de votre certificat ICI',
                        'help_block' => 'Example block-level help text here.'
            )))
                ->add('contenu', 'textarea', array(
                    'mapped'=>false,
                    'attr' => array(
                        'placeholder' => 'Placer le Contenu de votre certificat ICI',
                        'help_block' => 'Example block-level help text here.'
        )));

        foreach ($this->type_fichiers as $key => $value) {
            $builder->add($key, 'entity', array(
                'class' => 'Application\CertificatsBundle\Entity\CertificatsFiles',
                'query_builder' => function(EntityRepository $em) {
                    return $em->createQueryBuilder('a')
                                    ->select('a,b,c')
                                    ->leftJoin('a.certificats', 'b')
                                    ->leftJoin('b.typeCert', 'c')
                                    ->andwhere('c.fileType LIKE :mytype')
                                    ->setParameter('mytype', "$value%")
                                    ->orderBy('a.OriginalFilename', 'ASC');
                },
                'property' => 'OriginalFilename',
                'multiple' => false,
                'required' => false,
                'label' => $key,
                'empty_value' => '--- Choisir une option ---'
            ));
        }
        foreach ($this->type_password as $key => $value) {
            $builder->add($key, 'password', array(
                'required' => false,
                'widget_addon' => array(
                    'icon' => 'key',
                    'type' => 'prepend'
                ),
                'label' => $value,
            ));
        }
        $builder->add('operations_view', 'choice', array('label' => 'Opération_View',
            'multiple' => true,
            'choices' => $this->operations_view,
            'expanded' => true,
                //   'attr' => array('style' => 'width:100px', 'customattr' => 'customdata')
        ));
        $builder->add('operations_check', 'choice', array('label' => 'Opération_Check',
            'multiple' => true,
            'choices' => $this->operations_check,
            'expanded' => true,
                //   'attr' => array('style' => 'width:100px', 'customattr' => 'customdata')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
          //  'data_class' => 'Application\CertificatsBundle\Entity\CertificatsCenter'
        ));
    }

    public function getName() {
        return 'checkcert';
    }

}
