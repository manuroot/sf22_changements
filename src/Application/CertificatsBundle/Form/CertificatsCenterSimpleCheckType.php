<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Application\CertificatsBundle\Model\MyOpenSsl;

class CertificatsCenterSimpleCheckType extends AbstractType {

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


        $builder->add('operations_view', 'choice', array('label' => 'Opération_View',
            'multiple' => false,
            'choices' => $this->operations_view,
            'expanded' => true,
                //   'attr' => array('style' => 'width:100px', 'customattr' => 'customdata')
        ));
       /* $builder->add('operations_check', 'choice', array('label' => 'Opération_Check',
            'multiple' => true,
            'choices' => $this->operations_check,
            'expanded' => true,
                //   'attr' => array('style' => 'width:100px', 'customattr' => 'customdata')
        ));*/
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
