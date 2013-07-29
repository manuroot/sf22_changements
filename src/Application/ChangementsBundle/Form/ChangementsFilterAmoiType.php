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
use Application\ChangementsBundle\Entity\ChangementsRepository;
use Application\ChangementsBundle\Entity\Changements;
use Application\ChangementsBundle\Entity\ChangementsStatus;
use Application\RelationsBundle\Entity\Projet;
use Application\RelationsBundle\Repository\ProjetRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ChangementsFilterAmoiType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        //  $q=$em_->getProjetForRequeteBuilder
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
                    'class' => 'Changements',
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
                    'class' => 'Changements',
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
                    'class' => 'Changements',
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
                    'label' => 'Projet',
                    'property' => 'nomprojet',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    // working:
                    'class' => 'ApplicationRelationsBundle:Projet',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->getProjetForRequeteBuilder();
                    },
                ))
                /*
                  ->add('idProjet1', 'entity', array(
                  'label' => 'Projet1',
                  'property' => 'nomprojet',
                  'expanded' => false,
                  'multiple' => true,
                  'required' => false,
                  'mapped'=>false,
                  // working:
                  'class' => 'ApplicationChangementsBundle:Changements',
                  'query_builder' => function(EntityRepository $em) {
                  return $em->getProjetForRequeteBuilder();
                  },
                  )) */
                /* 'class' => 'ApplicationRelationsBundle:Projet',
                  'query_builder' => function(ProjetRepository $em) {
                  return $em->getProjetForRequeteBuilder();
                  }, */
                // 'property' => 'idprojet',
                //    'mapped' => false,
                //    'property_path'=>'idprojet',
                //    'mapped'=>false,
                /*  'class' => 'ApplicationChangementsBundle:Changements',
                  'choices'   => $this->em->getRepository('ApplicationsChangementsBundle:Changements')->getProjetForRequeteBuilder(),
                 */
                /* 'query_builder' => function(ChangementsRepository $em) {
                  //   $query_r=$em->getProjetForRequeteBuilder()->getQuery()->getArrayResult();
                  //  'query_builder' => function(EntityRepository $em) {
                  return $em->getProjetForRequeteBuilder();
                  }, */
                //     'choices' => $query_r,
                /*  return  $em->createQueryBuilder('a')
                  ->select('distinct b.id,b.nomprojet')
                  ->leftJoin('a.idProjet', 'b') */
                //  ->groupBy('b.id')



                /* 'class' => 'ApplicationChangementsBundle:Changements',
                  'query_builder' => function(ChangementsRepository $em){
                  return $em->getProjetForRequeteBuilder();
                  }, */
                /* 'class' => 'ApplicationChangementsBundle:Changements',
                  'query_builder' => function(EntityRepository $em) {
                  return $em->createQueryBuilder('u') //-> SELECT * FROM changements a
                  //  ->orderBy('u.nomprojet', 'ASC');
                  ->select('distinct u.id,b.nomprojet')
                  // -> LEFT JOIN projet su ON a.id = b.id

                  ->leftJoin('u.idProjet', 'b')

                  //  ->orderBy('b.nomprojet', 'ASC');

                  ;
                  // ->getQuery()
                  // ->getArrayResult();
                  }, */

                /* 'label' => 'Projet' */
                //     ))
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
                                ->leftJoin('u.idchangement', 'b')
                                ->Where('b.id IS NOT NULL')
                                  ->add('orderBy', 'u.nomUser ASC');
                    },
                    'empty_value' => '--- Choisir une option ---',
                    'property' => 'nomUser',
                    'multiple' => true,
                    //  'expanded'=>true,
                    'required' => false,
                    'label' => 'Utilisateurs'
        ));


        /*
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
          )); */
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
