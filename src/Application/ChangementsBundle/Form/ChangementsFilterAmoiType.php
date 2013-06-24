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
                ->add('nom','text',array( 
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                    'mapped'=>false,'required'=>false))
                    
                
                 ->add('dateDebut','text',array( 
                      'attr' => array(
            'placeholder'=>'> date debut'),
                     'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                        ),
                     'mapped'=>false,'required'=>false
                     ))
                
                
                 ->add('dateFin','text',array( 
                      'attr' => array(
            'placeholder'=>'> date Fin'),
                     'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                        ),
                     'mapped'=>false,'required'=>false))
           ->add('ticketExt','text',array('widget_addon' => array(
                        'icon' => 'tag',
                        'type' => 'prepend'
                    ),
               'mapped'=>false,'required'=>false))
        ->add('ticketInt','text',array('widget_addon' => array(
                        'icon' => 'tag',
                        'type' => 'prepend'
                    ),
            'mapped'=>false,'required'=>false))
             ->add('idEnvironnement', 'entity', array(
                    'class' => 'ApplicationRelationsBundle:Environnements',
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'Environnements'
                ))
                   ->add('idStatus', 'filter_entity', array(
                    'label' => 'Status',
                    'class' => 'Application\ChangementsBundle\Entity\ChangementsStatus',
                    'property' => 'nom',
                        'empty_value' => '--- Choisir une option ---',
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
                    
    }
    

    public function getName() {
        return 'changements_searchfilter';
    }

    /* public function setDefaultOptions(OptionsResolverInterface $resolver) {
      $resolver->setDefaults(array(
      'csrf_protection' => false,
      'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
      ));
      } */
}
