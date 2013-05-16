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
                        ->add('nom', 'text', array(
                             'widget_addon' => array(
                                'icon' => 'pencil',
                                'type' => 'prepend'
                            ),
                        ))
                       ->add('ticketExt',null,array(
                           'label'=>'Ticket Externe',
                             'widget_addon' => array(
                                'icon' => 'tag',
                                'type' => 'prepend'
                            ),
                           ))
               ->add('ticketInt',null,array(
                   'label'=>'Ticket Interne',
                             'widget_addon' => array(
                                'icon' => 'tag',
                                'type' => 'prepend'
                            ),))
        
                        ->add('dateDebut', 'date', array(
                            'label' => 'Date début',
                            'widget' => 'single_text',
                            'widget_addon' => array(
                                'icon' => 'time',
                                'type' => 'prepend'
                            ),
                        ))
                        ->add('dateFin', 'date', array(
                            'label' => 'Date Fin',
                            'widget' => 'single_text',
                            'widget_addon' => array(
                                'icon' => 'time',
                                'type' => 'prepend'
                            ),
                        ))
                 
                     ->add('description', 'textarea', array(
        'attr' => array(
            //'placeholder'=>'Description du changement',
            'cols'=>"60",
          //  'rows'=>"10",
            'class' => 'tinymce',
         'data-theme' => 'simple'
           
// simple, advanced, bbcode
        )))
                         
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
                
                ->add('idEnvironnement', 'entity', array(
                            'class' => 'ApplicationRelationsBundle:Environnements',
                            
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
                            'expanded' => true,
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
            'flowStep' => 1,
            'data_class' => 'Application\ChangementsBundle\Entity\Changements',
            'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'changements';
    }

}
