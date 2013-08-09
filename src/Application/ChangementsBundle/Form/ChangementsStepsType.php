<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ChangementsStepsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        switch ($options['flowStep']) {
            //====================================================
            // CASE 1   
            //====================================================
            case 1:
                $builder
                
               /* ->add('nom', 'genemu_jqueryautocomplete_entity', array(
                       'attr' => array(
                           'placeholder' => '5 a 30 car.'
                                    ),
                  'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ),
                  'class' => 'Application\ChangementsBundle\Entity\Changements',
                  'property' => 'nom',
                   'empty_data'=>false,
                  'configs' => array(
                  'minLength' => 1,
                  ),
                  )) */
                    
                     ->add('nom', 'genemu_jqueryautocomplete_entity', array(
                         'attr' => array(
                           'placeholder' => '5 a 30 car.'
                                    ),
                         'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ),
                         'label'=> "Nom",
            'class' => 'Application\ChangementsBundle\Entity\Changements',
            'property' => 'nom',
                         'empty_data'=>false,
                         'render_required_asterisk'=>true,
        ))
                    
            
   /* ->add('ticketExt', 'genemu_jqueryautocomplete_entity', array(
                            'attr' => array(
                           'placeholder' => 'ex: [1-XXXXXX | XXXXX]'
                                    ),
                           'label'=>'Ticket Externe',
                             'widget_addon' => array(
                                'icon' => 'tag',
                                'type' => 'prepend'
                            ),
                    'class' => 'Application\ChangementsBundle\Entity\Changements',
                   
                    'property' => 'ticketExt',
        'required'=>false,
                    'empty_data'=>false,
        //  'render_required_asterisk'=>'tyrty',
                      
                )) */
                                    /*
                    ->add('ticketInt', 'genemu_jqueryautocompleter_entity', array(
                            'attr' => array(
                           'placeholder' => 'ex: 12345 (5 a 10 car.)'
                                    ),
                           'label'=>'Ticket Interne',
                             'widget_addon' => array(
                                'icon' => 'tag',
                                'type' => 'prepend'
                            ),
                    'class' => 'Application\ChangementsBundle\Entity\Changements',
                   
                    'property' => 'ticketInt',
                          'query_builder' => function(EntityRepository $em) {
                                return $em->createQueryBuilder('u')
                                        
                                          ->where('u.ticketInt IS NOT NULL')
                                        ->orderBy('u.ticketInt', 'ASC');
                                   
                            },
                    
                ))   */
                 
                    
                     ->add('ticketExt',null,array(
                           'attr' => array(
                           'placeholder' => 'ex: [1-XXXXXX | XXXXX]'
                                    ),
                           'label'=>'Ticket Externe',
                             'widget_addon' => array(
                                'icon' => 'tag',
                                'type' => 'prepend'
                            ),
                           ))
                    
                    
              ->add('ticketInt',null,array(
                //   'render_required_asterisk'=>true,
                      'attr' => array(
                           'placeholder' => 'ex: 12345 (5 a 10 car.)'
                                    ),
                 
                   'label'=>'Ticket Interne',
                             'widget_addon' => array(
                                'icon' => 'tag',
                                'type' => 'prepend'
                            ),))
                               
                                    
        
                      ->add('dateDebut', 'datetime', array(
                    'label' => 'Date début',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                ))
                     ->add('dateFin', 'datetime', array(
                    'label' => 'Date Fin',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd HH:mm',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                         'required'=>false,
                ))
                    
                    ->add('idKind', 'entity', array(
                            'class' => 'ApplicationChangementsBundle:KindChangements',
                            'query_builder' => function(EntityRepository $em) {
                                return $em->createQueryBuilder('u')
                                        ->orderBy('u.nom', 'ASC');
                            },
                            'property' => 'nom',
                            'multiple' => false,
                            'required' => true,
                            'label' => 'Type',
                            'empty_value' => '--- Choisir une option ---'
                        ))
                                    
                    
                    
                     /*   ->add('dateDebut', 'date', array(
                            'label' => 'Date début',
                            'widget' => 'single_text',
                            'widget_addon' => array(
                                'icon' => 'time',
                                'type' => 'prepend'
                            ),
                        ))*/
                   /*     ->add('dateFin', 'date', array(
                            'label' => 'Date Fin',
                            'widget' => 'single_text',
                            'widget_addon' => array(
                                'icon' => 'time',
                                'type' => 'prepend'
                            ),
                        ))*/
                 
                   /*  ->add('description', 'textarea', array(
                    'label' => 'Description du Post',
                    'attr' => array(
                        'cols' => "35",
                       'rows' => "15",
                        'class' => 'tinymce',
                        )));*/
                   /* ->add('description', 'textarea', array(
                    'label' => 'Description de l\'opération',
                    'attr' => array(
                        'cols' => "25",
                         'rows' => "15",
                        'class' => 'tinymce',
                        )))*/
                    
                   ->add('description', 'textarea', array(
        'attr' => array(
            'class' => 'tinymce',
            'data-theme' => 'advanced' // simple, advanced, bbcode
        ),
                         'required' => false,
                     
    ))
                   /*  ->add('description', 'textarea')*/
                /*, array(
        'attr' => array(
            //'placeholder'=>'Description du changement',
            'cols'=>"60",
          //  'rows'=>"10",
            'class' => 'tinymce',
         'data-theme' => 'simple'
           
// simple, advanced, bbcode
        )))*/
                         
                       /* ->add('description','textarea',array(
                             'label' => 'Description',
                            //'widget' => 'single_text',
                            'attr' => array('class'=>'mytextarea'),
                        ))*/
                        
                        
                        ;
                break;
          
                //====================================================
                // CASE 3   
                //====================================================
              
            
            
                    case 2:
                $builder

                
                        ->add('demandeur', 'entity', array(
                            'class' => 'ApplicationRelationsBundle:ChronoUser',
                            'query_builder' => function(EntityRepository $em) {
                                return $em->createQueryBuilder('u')
                                        ->orderBy('u.nomUser', 'ASC');
                            },
                            'property' => 'nomUser',
                            'multiple' => false,
                            'required' => true,
                            'label' => 'Demandeur',
                            'empty_value' => '--- Choisir une option ---'
                        ))
                         
                               
                        ->add('idusers', 'entity', array(
                            'class' => 'ApplicationRelationsBundle:ChronoUser',
                            'query_builder' => function(EntityRepository $em) {
                                return $em->createQueryBuilder('u')
                                        ->orderBy('u.nomUser', 'ASC');
                            },
                            'property' => 'nomUser',
                            'multiple' => true,
                            'required' => true,
                            'label' => 'Utilisateurs'
                        ));
              
            
            
              break;
            //====================================================
            // CASE 2   
            //====================================================

            case 3:
                $builder
                        ->add('idProjet', 'entity', array(
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
                        ))
                        ->add('idapplis', 'entity', array(
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
                            break;
          
            case 4:
                $builder

                        /* ->add('picture',new DocumentsType())
                          ->add('avatar', 'file', array(
                          'data_class' => 'Symfony\Component\HttpFoundation\File\File'
                          )) */

                        //  ->add('product_image')
                        /*  ->add('picture', 'collection', array('type' => new DocfichierType(),
                          'allow_add' => true,
                          'by_reference' => true,
                          'allow_delete' => true,
                          'prototype' => true,
                          'prototype_name' => '__name__')) */

                        /* ->add('picture', 'collection',  array(
                          'label'  => 'Attachments',
                          'type' => new  DocumentsType(),
                          'prototype' => true,
                          'allow_add' => true,
                          'allow_delete' => true
                          )) */

                        //  ->add('fichier', new DocumentsType())
                
                 ->add('astreinte', 'checkbox', array('label' => 'Atreinte','required' => false))
                ->add('idEnvironnement', 'entity', array(
                            'class' => 'ApplicationRelationsBundle:Environnements',
                             'query_builder' => function(EntityRepository $em) {
                                return $em->createQueryBuilder('u')
                                        ->orderBy('u.nom', 'ASC');
                            },
                            'property' => 'nom',
                      'expanded' => 'true',
                            'multiple' => true,
                            'required' => true,
                            'label' => 'Environnement(s)'
                        ))
                    ->add('idStatus', 'entity', array(
                            'class' => 'ApplicationChangementsBundle:ChangementsStatus',
                            'property' => 'nom',
                            'multiple' => false,
                            'expanded' => false,
                            'required' => true,
                            'label' => 'Status',
                            'empty_value' => '--- Choisir une option ---'
                        ));
                            
                            
                       /* ->add('idEnvironnement', 'entity', array(
                            'class' => 'ApplicationChangementsBundle:Environnements',
                            'property' => 'nom',
                            //'expanded' => 'true',
                            'multiple' => true,
                            'required' => true,
                            'label' => 'Environnements'
                        ));*/
                
                   break;
    
            
            case 5:
                $builder
                        ->add('dateComep', 'date', array(
                            'label' => 'Date Comep',
                            'widget' => 'single_text',
                            'widget_addon' => array(
                                'icon' => 'time',
                                'type' => 'prepend'
                            ),
                            'required' => false,
                        ))
                        ->add('dateVsr', 'date', array(
                            'label' => 'Date VSR',
                            'widget' => 'single_text',
                            'widget_addon' => array(
                                'icon' => 'time',
                                'type' => 'prepend'
                            ),
                            'required' => false,
                        ));


                        /* ,'textarea',  array(
                          'widget' => 'textarea', */
                        /* 'widget_addon' => array(
                          'icon' => 'pencil',
                          'type' => 'prepend'
                          ), */

                        //   ))
                        

                break;

             /* case 6:
              $builder->add('confirmation', 'checkbox');
              break; */
        }

        //  ->add('fic')
        //    ->add('nbfiles')
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'flowStep' => '1',
            'data_class' => 'Application\ChangementsBundle\Entity\Changements',
            'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'changements';
    }

}















