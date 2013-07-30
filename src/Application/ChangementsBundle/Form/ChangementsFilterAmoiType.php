<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
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

    private $_em;

    public function __construct(EntityManager $entityManager) {
        $this->_em = $entityManager;
        // $em = $this->getDoctrine()->getManager();
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $em = $this->_em;
        $choices_projets = $em->getRepository('ApplicationChangementsBundle:Changements')->getProjetsForRequeteBuilder();
        $choices_demandeurs = $em->getRepository('ApplicationChangementsBundle:Changements')->getDemandeursForRequeteBuilder();

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
                /* ->add('idProjet','choice',array(
                  'choices' => $choices,
                  'required' => false,
                  'label' => 'Customer',
                  'expanded' => false,
                  'multiple' => true,
                  'mapped'=>false,
                  )) */
                /*  ->add('idProjet', 'entity', array(
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
                  )) */
                /* ->add('idProjet', 'entity', array(
                  'label' => 'Pklrojet',
                  'property' => 'nomprojet',
                  'expanded' => false,
                  'multiple' => true,
                  'required' => false,
                  // working:
                  'class' => 'ApplicationChangementsBundle:Changements',
                  'query_builder' => function(EntityRepository $em) {
                  return $em->createQueryBuilder('a')
                  ->select('distinct b.id,b.nomprojet,')
                  //  ->from('Application\ChangementsBundle\Entity\Changements', 'a')
                  ->leftJoin('a.idProjet', 'b')
                  ->groupBy('b.id')
                  ->add('orderBy', 'b.nomprojet ASC');
                  },
                  )) */
                ->add('idStatus', 'filter_entity', array(
                    'label' => 'Status',
                    'class' => 'Application\ChangementsBundle\Entity\ChangementsStatus',
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                        /* 'empty_value' => '--- Choisir une option ---', */
                ))
                /* ->add('demandeur', 'entity', array(
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
                ->add('idProjet', 'choice', array(
                    'choices' => $choices_projets,
                    'required' => false,
                    'label' => 'Projets',
                    'expanded' => false,
                    'multiple' => true,
                    'mapped' => false,
                ))
                ->add('demandeur', 'choice', array(
                    'choices' => $choices_demandeurs,
                    'required' => false,
                    'label' => 'Demandeur',
                    'expanded' => false,
                    'multiple' => false,
                    'mapped' => false,
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
                        //return $em->createQueryBuilder('u')>orderBy('u.nomUser', 'ASC');
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
