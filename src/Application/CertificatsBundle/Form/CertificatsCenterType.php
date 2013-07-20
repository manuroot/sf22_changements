<?php

namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\HttpFoundation\File\File;

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
                        'icon' => 'lock',
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
                 ->add('description','textarea',array(
                    'label' => 'Description',
                     'required' => false,
                    'widget_addon' => array(
                        'icon' => 'lock',
                        'type' => 'prepend'
                    ),))
                ->add('port','text',array(
                     'attr' => array(
                    'placeholder' => "ex: 80",
                ),
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('serviceName','text',array(
                    'widget_addon' => array(
                        'icon' => 'glass',
                        'type' => 'prepend'
                    ),))
                ->add('way','text',array(
                       'attr' => array(
                    'placeholder' => "ex: IN ou OUT",
                ),
                 
                    'widget_addon' => array(
                        'icon' => 'arrow-right',
                        'type' => 'prepend'
                    ),))
                ->add('statusFile', 'checkbox', array('label' => 'Verification','required' => false))
                
                  ->add('warningFile', 'checkbox', array('label' => 'Activer le Warning','required' => false))
                
               // ->add('picture')
               // , array(), array('edit' => 'list', 'link_parameters' => array('context' => 'symbols')))
              
/*->add('picture', 'sonata_type_model_list', array('required' => false),
                   array('link_parameters'=>array('context'=>'default',
                   'provider'=>'sonata.media.provider.image')))
        */
        
        
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
       /* $builder->add('fichier', 'file', array(
                    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                    'property_path' => 'fichier',
                    'required' => false,
                ));*/
          $builder->add('fichier', new CertificatsFilesType(),array(
              'label'=>'Fichier',
              'required'=>false));
                    
                 
        //  $builder->add('fichier','file');
               
              //    $builder->add('imageName','file');
        //
       //        ->add('fichier','file');
        
        
         /* ->add('fichier', 'file', array(
                          'data_class' => 'new CertificatsFiles',
                          ));*/
                  
                   //    ->add('fichier', new CertificatsFilesType());
        
      //  ->add('fichier','file');
        /*,'entity', array(
            'class' => 'Application\RelationsBundle\Entity\Filetype',
             'property' => 'FileType',
            'multiple' => false,
            'required' => true,
            'label' => 'Type',
            'empty_value' => '--- Choisir une option ---'
        ))*/
      //  ->add('fichier', 'file');
        /*, array(
                    'type' => new DocfichierType(),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                   /*'attr' => array(
                'class' => 'span5'
            )*/
        
        
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
        ))
   ->add('demandeur', 'entity', array(
                    'class' => 'ApplicationRelationsBundle:ChronoUser',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nomUser', 'ASC');
                    },
                    'property' => 'nomUser',
                    'multiple' => false,
                    'required' => false,
                    'label' => 'Demandeur',
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
            
          
              $builder->add('idEnvironnement', 'entity', array(
            'class' => 'Application\RelationsBundle\Entity\Environnements',
            //'class' => 'ApplicationRelationsBundle:Environnements',
             'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
            },
            'property' => 'nom',
            'multiple' => false,
            'required' => true,
            'label' => 'Environnement',
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
            'required' => false,
            'label' => 'Applications'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
       //      'data_class' => null,
          'data_class' => 'Application\CertificatsBundle\Entity\CertificatsCenter',
            'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'moncert';
    }

}
