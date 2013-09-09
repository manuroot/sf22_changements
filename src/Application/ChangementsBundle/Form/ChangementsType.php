<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ChangementsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->setAttribute('show_legend', false); // no legend for main form
       
$builder
  
          ->add('nom', null, array(
              'label'=>'Nom',
              'attr' => array(
                           'placeholder' => '5 a 30 car.'
                                    ),
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )))
           
        /*  ->add('ticketExt', 'genemu_jqueryautocompleter_entity', array(
            'property' => 'ticketExt',
                  'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ),
                  'class' => 'Application\ChangementsBundle\Entity\Changements',
                   'configs' => array(
                  'minLength' => 1,
                  ),
                  'required'=>false,
                  )) */
        
         /*  ->add('ticketInt', 'genemu_jqueryautocomplete_text', array(
                  //  'route_name' => 'ajax_form_request_ticketint',
                    'configs' => array('minLength' => 3),
                    'data_class' => 'Application\ChangementsBundle\Entity\Changements',
                    'mapped' => false,
              //      'data' => $this->getData()-> getTicketInt()
                ))*/
        
        /*  ->add('ticketInt', 'genemu_jqueryautocompleter_entity', array(
                   'label' => 'Ticket Externe',  
                  'widget_addon' => array(
                  'icon' => 'tag',
                  'type' => 'prepend'
                  ),
                  'required'=>false,   
              'empty_value'=>false,
                  'class' => 'Application\ChangementsBundle\Entity\Changements',
                   'query_builder' => function($repository) {
                return $repository->createQueryBuilder('c')
                    ->where('c.ticketInt IS NOT NULL');
                    
            },  
                  'property' => 'ticketInt',
                
                  )) */
            
              ->add('ticketExt', null, array(
                  
                    'label' => 'Ticket Externe',
                  
                  'attr' => array(
                           'style' => 'width:150px',
                           'placeholder' => 'ex: [1-XXXXXX | XXXXX]'
                                    ),
                    'widget_addon' => array(
                        'icon' => 'tag',
                        'type' => 'prepend'
                    ),))
        
                ->add('ticketInt', null, array(
                     'attr' => array(
                          'style' => 'width:150px',
                        'placeholder' => 'ex: 12345 (5 a 10 car.)'
                                    ),
                     
                    'label' => 'Ticket Interne',
                    'widget_addon' => array(
                        'icon' => 'tag',
                        'type' => 'prepend'
                    ),))
                  /* ->add('ticketExt',null,array('label'=>'Ticket Externe'))
                  ->add('ticketInt',array('label'=>'Ticket Interne')) */
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
                    'required' => false,
                ))
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
                ))
        
         ->add('dateDemande', 'date', array(
                    'label' => 'Date Demande',
                    'widget' => 'single_text',
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                    'required' => true,
                ))
        
        ->add('description', 'textarea', array(
        'attr' => array(
            'class' => 'tinymce',
            'data-theme' => 'advanced' // simple, advanced, bbcode
        ),
              'required' => false,
    ))
       //  ->add('description', 'textarea')
              /*  ->add('description', 'textarea', array(
                    'attr' => array(
                          'class' => 'tinymce',
                         'width'=>'300px',
                    // 'data-theme' => 'simple'
// simple, advanced, bbcode
                        )))*/
                //   ->add('description')
                /* ,'textarea',  array(
                  'widget' => 'textarea', */
                /* 'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ), */

                //   ))

                /*
                  ->add('idStatus', 'genemu_jqueryselect2_entity', array(
                  'class' => 'ApplicationChangementsBundle:ChangementsStatus',
                  'property' => 'nom',
                  ))

                 */ /* ->add('country', 'genemu_jqueryselect2_country',
                  array('property_path'=>false)  ) */
                ->add('idStatus', 'entity', array(
                    'class' => 'ApplicationChangementsBundle:ChangementsStatus',
                    'property' => 'nom',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                    'label' => 'Status',
                 //   'empty_value' => '--- Choisir une option ---'
                ))
        
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
                            
                  ->add('idKind', 'entity', array(
                    'class' => 'ApplicationChangementsBundle:KindChangements',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
                    },
                    'property' => 'nom',
                    'multiple' => false,
                    'required' => false,
                    'label' => 'Type',
                    'empty_value' => '--- Choisir une option ---'
                ));
                    
                            
                  $builder->add('astreinte', 'checkbox', array('label' => 'Astreinte','required' => false));
                  
               $factory = $builder->getFormFactory();
           /*   $projet_id = $builder->getData()->getIdProjet();
        $refreshApplis = function ($form, $idProjet) use ($factory) {
       $form->add($factory->createNamed('entity','idapplis',null, array(
                'class'         => 'ApplicationRelationsBundle:Applis',
                'property'      => 'nomapplis',
                'label'         => 'Applications',
                'query_builder' => function (EntityRepository $repository) use ($idProjet) {
                                       $qb = $repository->createQueryBuilder('idapplis')
                                                        ->innerJoin('idapplis.idProjets', 'projet');
 
                                       if($idProjet instanceof Projet) {
                                           $qb = $qb->where('idapplis.idProjets = :projet')
                                                    ->setParameter('projet', $idProjet);
                                       } elseif(is_numeric($idProjet)) {
                                           $qb = $qb->where('idapplis.id = :projet_id')
                                                    ->setParameter('projet_id', $idProjet);
                                       } else {
                                           //?
                                           $qb = $qb->where('idapplis.id = 1');
                                       }
 
                                       return $qb;
                                   }
                 )));
        };*/
       
      /*    $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshApplis) {
            $form = $event->getForm();
            $data = $event->getData();

            if($data == null)
               $refreshApplis($form, null); //As of beta2, when a form is created setData(null) is called first
 
            if($data instanceof Projet) {
                $refreshApplis($form, $data->getIdApplis()->getIdProjets());
                }
        });
 
        $builder->addEventListener(FormEvents::PRE_BIND, function (DataEvent $event) use ($refreshApplis) {
           $form = $event->getForm();
            $data = $event->getData();
 
            if(array_key_exists('idProjet', $data)) {
                $refreshApplis($form, $data['idProjet']);
            }
        });*/
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

                /* ->add('picture',new DocumentsType())
                  ->add('avatar', 'file', array(
                  'data_class' => 'Symfony\Component\HttpFoundation\File\File'
                  )) */

                //  ->add('product_image')
                  $builder->add('picture', 'collection', array(
                    'type' => new ChangementDocumentsType(),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                       'prototype' => true,
                      'label'=>false,
                   /*'attr' => array(
                'class' => 'span5'
            )*/
                   // 'prototype' => true,
                    //'prototype_name' => '__name__'
                    ))
                            
       /*  ->add('picture','collection', array(
        'type' => new DocfichierType(),
        'allow_add' => true,
        'allow_delete' => true,
        'prototype' => true,
        'widget_add_btn' => array('label' => 'add email', 'attr' => array('class' => 'btn btn-primary')),
        'options' => array( // options for collection fields
            'widget_remove_btn' => array('label' => 'remove', 'attr' => array('class' => 'btn btn-primary')),
            'attr' => array('class' => 'span3'),
            'widget_addon' => array(
                'type' => 'prepend',
                'text' => '@',
            ),
            'widget_control_group' => false,
        )
    ))*/
                            /*
   ->add('picture','collection', array(
        'type' => new DocfichierType(),
                    'allow_add' => true,
                    'by_reference' => false,
                    'allow_delete' => true,
        'widget_add_btn' => array(
            'icon' => 'plus-sign',
            'label' => 'add email'
         ),
    ))*/
    
                    
                            /*
                ->add('picture', 'collection', array(
                    'type' => new DocfichierType(),
                    'allow_add' => true,
                    'by_reference' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    //'prototype_name' => '__name__'
                    ))*/

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
                    'label' => 'Environnements'
                ))
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
                    'attr' => array(
                          'style' => 'height:150px',
                        'placeholder' => 'ex: 12345 (5 a 10 car.)'
                                    ),
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

        //  ->add('fic')
        //    ->add('nbfiles')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
       'data_class' => 'Application\ChangementsBundle\Entity\Changements',
            'cascade_validation' => true,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'intention'       => 'task_item',
        ));
    }

    public function getName() {
        return 'changements';
    }

}
