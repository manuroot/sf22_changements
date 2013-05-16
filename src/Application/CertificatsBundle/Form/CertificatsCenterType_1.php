<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CertificatsCenterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('fileName')
              /*  ->add('myid','hidden',
                        array(
                    "mapped" => false,
                            'data' => $data->getId()
                        ))*/
                ->add('cnName')
                // ->add('startDate')
                //  ->add('endTime')
                // ->add('addedDate')
                ->add('serverName')
                ->add('port')
                ->add('serviceName')
                ->add('way')
                ->add('statusFile', 'checkbox', array('label' => 'Verification'))
               // ->add('picture', array(), array('edit' => 'list', 'link_parameters' => array('context' => 'symbols')))
                /*
->add('picture', 'sonata_type_model_list', array('required' => false),
                   array('link_parameters'=>array('context'=>'default',
                   'provider'=>'sonata.media.provider.image')))*/
        ;
        $builder->add('startDate', 'date', array(
            'label' => 'Date début',
            'widget' => 'single_text'
        ));
        $builder->add('addedDate', 'date', array(
            'label' => 'Date d\'ajout',
            'widget' => 'single_text'
        ));

        $builder->add('endTime', 'date', array(
            'label' => 'Date de Fin',
            'widget' => 'single_text'
        ));
        $builder->add('typeCert', 'entity', array(
            'class' => 'Application\CertificatsBundle\Entity\CertificatsFiletype',
            'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.fileType', 'ASC');
            },
            'property' => 'FileType',
            'multiple' => false,
            'required' => true,
            'label' => 'Type',
            'empty_value' => '--- Choisir une option ---'
        ));

        $builder->add('project', 'entity', array(
            //'class' => 'Application\CertificatsBundle\Entity\CertificatsProjet',
            'class' => 'ApplicationCertificatsBundle:CertificatsProjet',
             'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.nomprojet', 'ASC');
            },
            'property' => 'nomprojet',
            'multiple' => false,
            'required' => true,
            'label' => 'Projet',
           'empty_value' => '--- Choisir une option ---'
        ));
        $builder->add('idapplis', 'entity', array(
            'class' => 'ApplicationCertificatsBundle:CertificatsApplis',
           'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.nomapplis', 'ASC');
            },
            'property' => 'nomapplis',
            'multiple' => true,
            'required' => true,
            'label' => 'Applications'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CertificatsBundle\Entity\CertificatsCenter'
        ));
    }

    public function getName() {
        return 'moncert';
    }

}
