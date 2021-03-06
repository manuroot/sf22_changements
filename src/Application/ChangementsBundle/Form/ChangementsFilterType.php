<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType as TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Lexik\Bundle\FormFilterBundle\Filter\ORM\Expr;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;

class ChangementsFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
         
            ->add(
                'nom',
                'filter_text',
             
                array(
                        'compound'=>true,
                    'text_options'=>array( 'attr' => array('icon' => 'icon-user')),
                        'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                )
            )
        
           ->add('dateFin', 'filter_date_range', array(
                    'label' => 'Date début',
                    'left_date_options' => array(
                                        'attr' => array('placeholder' => '> date Fin','style'=>'width:150px'),
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                    'right_date_options' => array(
                                 'attr' => array('placeholder' => '< date Fin','style'=>'width:150px'),
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                ))
                ->add('dateDebut', 'filter_date_range', array(
                    'label' => 'Date début',
                    'left_date_options' => array(
                             'attr' => array('placeholder' => '> date Début','style'=>'width:150px'),
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                    'right_date_options' => array(
                            'attr' => array('placeholder' => '< date Début','style'=>'width:150px'),
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                ))
                
                  ->add('ticketExt', 'filter_text', array(
                           'text_options'=>array( 
                               'attr' => array('icon' => 'icon-tags',
                                   'placeholder' => '[1-XXXXXX | XXXXX]'
                                   ),
                             ),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                 //     'widget_controls' => false,
                  /*    'label'=> array('widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'append'
                    )),*/
                   /* 'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),*/
                      ))
                
            
                
                
                   ->add('ticketInt', 'filter_text', array(
                                'text_options'=>array( 'attr' => array(
                                    'icon' => 'icon-tags',
                                        'placeholder' => 'ex: 12345 (5 a 10 car.)'
                                    )),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                
                
                /* ->add('ticketExt', 'genemu_jqueryautocomplete_entity', array(
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
              
                ->add('idProjet', 'filter_entity', array(
                    'label' => 'Projet',
                    'class' => 'Application\RelationsBundle\Entity\Projet',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nomprojet', 'ASC');
                    },
                    'property' => 'nomprojet',
                                'required' => false,
                     'empty_value' => '--- Choisir une option ---', 
                ))
                ->add('demandeur', 'filter_entity', array(
                    'label' => 'Demandeur',
                    'class' => 'Application\RelationsBundle\Entity\ChronoUser',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nomUser', 'ASC');
                    },
                    'property' => 'nomUser',
                                'required' => false,
                     'empty_value' => '--- Choisir une option ---', 
                ))
                ->add('idStatus', 'filter_entity', array(
                              'empty_value' => '--- Choisir une option ---', 
                    'label' => 'Status',
                    'class' => 'Application\ChangementsBundle\Entity\ChangementsStatus',
                    'property' => 'nom'
                ))
                ->add('idusers', 'filter_entity', array(
                    //->add('idusers', 'filter_entity', array(
                    'class' => 'ApplicationRelationsBundle:ChronoUser',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nomUser', 'ASC');
                    },
                            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                                   if (!empty($values['value'])) {
                                       $qb = $filterQuery->getQueryBuilder();
                                       $qb->andWhere($filterQuery->getExpr()->eq($field, $values['value']));
                               //      $queryBuilder->andWhere('e.nomUser LIKE :name')
                           // ->setParameter('name', '%' . $values['value'] . '%');
                   ;
                        }
                    },
                            
                 /* 'apply_filter' => function (QueryBuilder $queryBuilder, Expr $expr, $field, array $values) {
                        if (!empty($values['value'])) {
                               $user = $values['value'];
                                     $queryBuilder->andWhere('e.nomUser LIKE :name')
                            ->setParameter('name', '%' . $values['value'] . '%');
                   
                        }
                    },*/
                    //   'property' => 'nomUser',
                     // 'multiple' => true,
                     /*'required' => false,
                            'mapped'=>false,*/
                                'required' => false,
                     'empty_value' => '--- Choisir une option ---', 
                    'label' => 'Utilisateurs',
                        /*   'apply_filter' => function (QueryBuilder $queryBuilder, Expr $expr, $field, array $values) {
                          if (!empty($values['value'])) {
                          // add the join if you need it and it not already added

                          $queryBuilder->leftJoin('a.idEnvironnement','g');

                          $queryBuilder->andWhere('g.id = :name')
                          ->setParameter('name', $values['value']);
                          }
                          }, */
                ))
                ->add('idEnvironnement', 'filter_entity', array(
                    'label' => 'Environnement',
                    'class' => 'Application\RelationsBundle\Entity\Environnements',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
                    },
                             'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                                   if (!empty($values['value'])) {
                                       $qb = $filterQuery->getQueryBuilder();
                                       $qb->andWhere($filterQuery->getExpr()->eq($field, $values['value']));
                               //      $queryBuilder->andWhere('e.nomUser LIKE :name')
                           // ->setParameter('name', '%' . $values['value'] . '%');
                   ;
                        }
                    },
                    /*'apply_filter' => function (QueryBuilder $queryBuilder, Expr $expr, $field, array $values) {
                        if (!empty($values['value'])) {
                            //fieldNames
                            // add the join if you need it and it not already added
                            //  $queryBuilder->leftJoin('a.idusers', 'e');
                           // $env = $values['value'];
                            
                           $queryBuilder->andWhere("g.nom='" . $values['value'] . "'");
                          //  ->setParameter('name', '%' . $values['value'] . '%');
                          //  ->setParameter('name', $values['value'] );
                        }
                    },*/
                    /* 'multiple' => true,*/
                      'required' => false,
                     'empty_value' => '--- Choisir une option ---', 
                    'property' => 'nom'
                ));

        /*  $listener = function(FormEvent $event) {
          // Is data empty?
          foreach ($event->getData() as $data) {
          if (is_array($data)) {
          foreach ($data as $subData) {
          if (!empty($subData))
          return;
          }
          }
          else {
          if (!empty($data))
          return;
          }
          }

          $event->getForm()->addError(new FormError('Filter empty'));
          };
          $builder->addEventListener(FormEvents::POST_BIND, $listener); */
    }

    public function getName() {
        return 'changements_filter';
    }

    /* public function setDefaultOptions(OptionsResolverInterface $resolver) {
      $resolver->setDefaults(array(
      'csrf_protection' => false,
      'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
      ));
      } */
}
