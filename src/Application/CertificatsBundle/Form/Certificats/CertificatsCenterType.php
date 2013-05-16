<?php

namespace Application\CertificatsBundle\Form\Certificats;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CertificatsCenterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('fileName' ,'text',array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
              /*  ->add('myid','hidden',
                        array(
                    "mapped" => false,
                            'data' => $data->getId()
                        ))*/
                ->add('cnName','text',array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                // ->add('startDate')
                //  ->add('endTime')
                // ->add('addedDate')
                ->add('serverName','text',array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('port','text',array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('serviceName','text',array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('way','text',array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('statusFile', 'checkbox', array('label' => 'Verification'))
                
               // ->add('picture', array(), array('edit' => 'list', 'link_parameters' => array('context' => 'symbols')))
                /*
->add('picture', 'sonata_type_model_list', array('required' => false),
                   array('link_parameters'=>array('context'=>'default',
                   'provider'=>'sonata.media.provider.image')))*/
        
        
        
         ->add('startDate', 'date', array(
                    'label' => 'Date début',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                ));
        $builder->add('addedDate', 'datetime', array(
            'label' => 'Date d\'ajout',
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'yyyy-MM-dd',
            'widget_addon' => array(
                'icon' => 'time',
                'type' => 'prepend'
            ),
        ));
        $builder->add('endTime', 'datetime', array(
            'label' => 'Date de fin',
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => 'yyyy-MM-dd',
            'widget_addon' => array(
                'icon' => 'time',
                'type' => 'prepend'
            ),
        ));
        
        
        
        /*
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
        ));*/
        $builder->add('typeCert', 'entity', array(
            'class' => 'Application\RelationsBundle\Entity\Filetype',
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
            'class' => 'ApplicationRelationsBundle:Projet',
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
            'class' => 'ApplicationRelationsBundle:Applis',
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
