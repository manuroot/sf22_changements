<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ChangementsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->setAttribute('show_legend', false); // no legend for main form
        // $child = $builder->create('user', new SomeSubFormType(), array('show_child_legend' => true)); // but legend for this subform
        //  $builder->add($child);
        $builder
                //   ->add('nom')
                ->add('nom', null, array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )))

                /*           $builder
                  //   ->add('nom')
                  ->add('nom', 'genemu_jqueryautocomplete_entity', array(
                  'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ),
                  'class' => 'Application\ChangementsBundle\Entity\Changements',
                  'property' => 'nom',
                  'configs' => array(
                  'minLength' => 0,
                  ),
                  )) */
                /*   ->add('nom', 'text',  array(
                  "help_inline"=>"Please specify some understandable title", */
                /*  'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ),

                  )) */
                // Suggestions with doctrine orm
                /*  ->add('nom', 'genemu_jqueryautocomplete_entity', array(
                  'class' => 'ApplicationChangementsBundle\Entity\Changements',
                  'property' => 'nom',
                  ))

                 */
                //  $builder
                //   ->add('nom')
             /*   ->add('ticketExt', 'genemu_jqueryautocompleter_entity', array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                    'required' => false,
                    'class' => 'Application\ChangementsBundle\Entity\Changements',
                    'property' => 'ticketExt',
                        'configs' => array(
                          'minLength' => 2,
                          ), 
                ))*/
                   ->add('ticketExt',null,array(
                  'label'=>'Ticket Externe',
                  'widget_addon' => array(
                  'icon' => 'tag',
                  'type' => 'prepend'
                  ),
                  )) 
                ->add('ticketInt', null, array(
                    'label' => 'Ticket Interne',
                    'widget_addon' => array(
                        'icon' => 'tag',
                        'type' => 'prepend'
                    ),))
                /*    ->add('ticketExt',null,array('label'=>'Ticket Externe'))
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
                ->add('description', 'textarea', array(
                    'attr' => array(
                        'cols' => "60",
                        //  'rows'=>"10",
                        'class' => 'tinymce',
                    // 'data-theme' => 'simple'
// simple, advanced, bbcode
                        )))
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
                    'expanded' => true,
                    'required' => true,
                    'label' => 'Status',
                    'empty_value' => '--- Choisir une option ---'
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
                ))

                /* ->add('picture',new DocumentsType())
                  ->add('avatar', 'file', array(
                  'data_class' => 'Symfony\Component\HttpFoundation\File\File'
                  )) */

                //  ->add('product_image')
                ->add('picture', 'collection', array('type' => new DocfichierType(),
                    'allow_add' => true,
                    'by_reference' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'prototype_name' => '__name__'))

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
        ));
    }

    public function getName() {
        return 'changements';
    }

}
