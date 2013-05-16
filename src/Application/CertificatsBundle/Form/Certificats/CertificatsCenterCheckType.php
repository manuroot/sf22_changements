<?php

namespace Application\CertificatsBundle\Form\Certificats;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CertificatsCenterCheckType extends AbstractType {

    private $type_certificats;

    public function __construct()
  {
    $this->type_certificats = $this->mycert();
  }

    public function buildForm(FormBuilderInterface $builder, array $options) {
         /* ->add('contenu', 'textarea', array(
                 //    'attr' => array('rows' => 20,'cols' => 20)
              */
              $builder->add('contenu', 'textarea', array(
                    'attr'=> array(
                        'placeholder' => 'Placer le Contenu de votre certificat ICI',
                         'help_block'    => 'Example block-level help text here.'
                       /*  'cols'=>"100",
                       'rows' => "10",*/
                    //   'class'=>'ui-spinner-box',
                    
                    )))
                       
                ->add('opecert', 'choice', array(
 
                    'widget_addon' => array(
            'icon' => 'time',
            'type' => 'prepend'
        ),
                    'label' => 'Certificat',
                    'multiple' => false,
                    'choices' => array(
                        0 => 'Certificats(crt)',
                        2 => 'Certificats(pem)',
                        'Autorité(crt)',
                        'Autorité(pem)',
                    ),
                  //  'attr' => array('style' => 'width:100px', 'customattr' => 'customdata')
                ))
                ->add('typecert', 'choice', array('label' => 'Opération',
                    'multiple' => false,
                    'choices' => $this->type_certificats,
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

    protected function mycert() {
        $liste_operations_certificat = array(
            'View csr' => 'View csr',
            'View crt' => 'View crt',
            'View der' => 'View der',
            'View bundle' => 'View bundle',
            'View key' => 'View key',
            'View p12' => 'View p12',
            'View crl' => 'View crl',
            'Check crt/key' => 'check crt/key',
            'Create p12' => 'Create p12',
            'Bundle crt/key' => 'Bundle crt/key',
            'Decrypt priv_key' => 'Decrypt priv_key',
            'Convert der->pem' => 'Convert der->pem',
            'Convert pem->der' => 'Convert pem->der',
            'Convert p12->crt/key' => 'Convert p12->crt/key',
            'Parse x509' => 'Parse x509',
                //'Download p12'=> 'Download p12',
        );
        return array_keys($liste_operations_certificat);
    }

}
