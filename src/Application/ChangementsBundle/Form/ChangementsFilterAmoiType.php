<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\QueryBuilder;

class ChangementsFilterAmoiType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        
        $builder
                ->add('nom', 'genemu_jqueryautocomplete_text', array(
                    'label' => 'Nom',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                    'configs' => array('minLength' => 2),
                    'mapped' => false, 'required' => false,
                    'route_name' => 'ajax_nom',
                    'class' => 'Application\ChangementsBundle\Entity\Changements',
                ))

                /*       ->add('description', 'genemu_jqueryautocomplete_text', array(
                  'label' => 'Description',
                  'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ),
                  'mapped' => false, 'required' => false,
                  'route_name' => 'ajax_description',
                  'class' => 'Application\ChangementsBundle\Entity\Changements',
                  )) */
                /* ->add('nom', 'text', array(
                  'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ),
                  'mapped' => false, 'required' => false)) */
                ->add('description', 'text', array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                    'mapped' => false, 'required' => false))
                ->add('dateDebut', 'text', array(
                    'attr' => array(
                        'placeholder' => '> date debut'),
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                    'mapped' => false, 'required' => false
                ))
                ->add('dateDebut_max', 'text', array(
                    'attr' => array(
                        'placeholder' => '< date debut'),
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                    'mapped' => false, 'required' => false
                ))
                ->add('dateFin', 'text', array(
                    'attr' => array(
                        'placeholder' => '> date Fin'),
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                    'mapped' => false, 'required' => false))
                ->add('dateFin_max', 'text', array(
                    'attr' => array(
                        'placeholder' => '< date Fin'),
                    'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                    ),
                    'mapped' => false, 'required' => false))

                /*  ->add('ticketExt', 'genemu_jqueryautocompleter_entity', array(
                  'label'=>'Ticket Externe XX',
                  'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ),
                  'required' => false,
                  'mapped'=>false
                  'empty_value'=>false,
                  'class' => 'Application\ChangementsBundle\Entity\Changements',
                  'query_builder' => function(EntityRepository $em) {
                  return $em->createQueryBuilder('u')
                  ->where('u.ticketExt IS NOT NULL')
                  ->orderBy('u.ticketExt', 'ASC');
                  },
                  'property' => 'ticketExt',
                  // 'configs' => array(
                  //  'minLength' => 2,
                  //  ),
                  )) */



                /*  ->add('ticketExt','text',array('widget_addon' => array(
                  'icon' => 'tag',
                  'type' => 'prepend'
                  ),
                  'mapped'=>false,'required'=>false)) */

                /* ->add('ticketExt','text',array(
                  'attr' => array('style' => 'width:120px'),
                  'label'=>'Ticket Externe',
                  'widget_addon' => array(
                  'icon' => 'tag',
                  'type' => 'prepend'
                  ),
                  'mapped'=>false,'required'=>false)) */
                ->add('ticketInt', 'genemu_jqueryautocomplete_text', array(
                    'attr' => array('style' => 'width:120px'),
                    'label' => 'Ticket Interne',
                    'widget_addon' => array(
                        'icon' => 'tag',
                        'type' => 'prepend'
                    ),
                    'configs' => array('minLength' => 2),
                    'mapped' => false, 'required' => false,
                    'route_name' => 'ajax_ticketint',
                    'class' => 'Application\ChangementsBundle\Entity\Changements',
                ))
                ->add('ticketExt', 'genemu_jqueryautocomplete_text', array(
                    'attr' => array('style' => 'width:120px'),
                    'label' => 'Ticket Externe',
                    'widget_addon' => array(
                        'icon' => 'tag',
                        'type' => 'prepend'
                    ),
                    'configs' => array('minLength' => 2),
                    'mapped' => false, 'required' => false,
                    'route_name' => 'ajax_ticketext',
                    'class' => 'Application\ChangementsBundle\Entity\Changements',
                ))

                /*
                 * de apy
                 * 


                  SELECT
                  DISTINCT p0_.nomprojet AS nomprojet0
                  FROM
                  changements_main c1_
                  LEFT JOIN projet_main p0_ ON c1_.id_projet = p0_.id
                  LEFT JOIN chrono_user c2_ ON c1_.demandeur = c2_.id
                  LEFT JOIN changements_status c3_ ON c1_.id_status = c3_.id
                  LEFT JOIN changements_environnements c5_ ON c1_.id = c5_.changements_id
                  LEFT JOIN environnement_main e4_ ON e4_.id = c5_.environnements_id
                  GROUP BY
                  c1_.id
                  ORDER BY
                  p0_.nomprojet ASC


                  'query_builder' => function(SlotRepository $cr) use ($users) {
                  return $cr->findAllFreeSlotByUsers($users);
                  }
                 */
                /* ->add('ticketExt', 'genemu_jqueryautocompleter_entity', array(
                  'label'=>'Ticket Externe XX',
                  'widget_addon' => array(
                  'icon' => 'pencil',
                  'type' => 'prepend'
                  ),
                  'required' => false,
                  'mapped'=>false,
                  'empty_value'=>false,
                  'class' => 'Application\ChangementsBundle\Entity\Changements',
                  'query_builder' => function(EntityRepository $em) {
                  return $em->createQueryBuilder('u')
                  ->where('u.ticketExt IS NOT NULL')
                  ->orderBy('u.ticketExt', 'ASC');
                  },
                  'property' => 'ticketExt',
                  // 'configs' => array(
                  //  'minLength' => 2,
                  //  ),
                  )) */
                /*    ->add('ticketInt', 'text', array(
                  'attr' => array('style' => 'width:120px'),
                  'label' => 'Ticket Interne',
                  'widget_addon' => array(
                  'icon' => 'tag',
                  'type' => 'prepend'
                  ),
                  'mapped' => false, 'required' => false)) */
                ->add('idEnvironnement', 'entity', array(
                    'class' => 'ApplicationRelationsBundle:Environnements',
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'Environnements'
                ))
                ->add('idProjet', 'entity', array(
                    
                    /*'class' => 'ApplicationRelationsBundle:Projet',
                      'query_builder' => function(EntityRepository $em) {
                      return $em->createQueryBuilder('a')
                               ->orderBy('a.nomprojet', 'ASC')
                              ;
                              
                      },*/
                      
                    'class' => 'ApplicationChangementsBundle:Changements',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u') //-> SELECT * FROM changements a
                              //  ->orderBy('u.nomprojet', 'ASC');
                               ->select('distinct u.id,b.nomprojet')
                                // -> LEFT JOIN projet su ON a.id = b.id
                                 
                                  ->leftJoin('u.idProjet', 'b')
                                
                             //  ->orderBy('b.nomprojet', 'ASC');
                            /*
                                ->leftJoin('a.demandeur', 'c')
                                ->leftJoin('a.idStatus', 'd')
                                ->leftJoin('a.picture', 'f')
                                ->addGroupBy('a.id')
                                ->add('orderBy', 'b.nomprojet DESC');*/
                                ;
                        // ->getQuery()
                       // ->getArrayResult();
                    },
                    'property' => 'nomprojet',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'Projet'
                ))
                ->add('idStatus', 'filter_entity', array(
                    'label' => 'Status',
                    'class' => 'Application\ChangementsBundle\Entity\ChangementsStatus',
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                        /* 'empty_value' => '--- Choisir une option ---', */
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
                ))
                /*
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
                  )) */
                ->add('idusers', 'entity', array(
                    'class' => 'ApplicationRelationsBundle:ChronoUser',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nomUser', 'ASC');
                    },
                    'empty_value' => '--- Choisir une option ---',
                    'property' => 'nomUser',
                    'multiple' => true,
                    //  'expanded'=>true,
                    'required' => false,
                    'label' => 'Utilisateurs'
        ));
        /* 'query_builder' => function(EntityRepository $er) use ($options) {
          return $er->createQueryBuilder('u')
          ->where('u.section = :id')
          ->setParameter('id', $options['id'])
          ->orderBy('u.root', 'ASC')
          ->addOrderBy('u.lft', 'ASC');
          }, */
    }

    public function getName() {
        return 'changements_searchfilter';
    }

    /* public function setDefaultOptions(OptionsResolverInterface $resolver) {
      $resolver->setDefaults(array(
      'csrf_protection' => false,
      'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
      ));
      } 
     * 
     * SELECT DISTINCT p1_.id AS id0, p1_.nomprojet AS nomprojet1
FROM changements_main c0_
LEFT JOIN projet_main p1_ ON c0_.id_projet = p1_.id
     */
}
