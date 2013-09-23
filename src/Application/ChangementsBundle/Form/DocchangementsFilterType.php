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

class DocchangementsFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
       $builder
                ->add('name','text',array( 
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                    'label'=>'Nom',
                    'mapped'=>false,'required'=>false))
               
                  /*   ->add('path','text',array( 
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                    'mapped'=>false,'required'=>false))
                    */
                  ->add('md5','text',array( 
                      'label'=>'Md5',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                    'mapped'=>false,'required'=>false))
               
                 ->add('updatedAt','text',array( 
                     'label'=>'Date Update Min',
                      'attr' => array(
            'placeholder'=>'> updatedAt'),
                     'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                        ),
                     'mapped'=>false,'required'=>false
                     ))
                
                     ->add('updatedAt_max','text',array( 
                          'label'=>'Date Update Max',
                      'attr' => array(
            'placeholder'=>'< updatedAt'),
                     'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                        ),
                     'mapped'=>false,'required'=>false
                     ))
                
         
               /* ->add('OriginalFilename', 'genemu_jqueryautocomplete_text', array(
                    'label' => 'OriginalFilename',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                    'configs' => array('minLength' => 2),
                    'mapped' => false, 'required' => false,
                    'route_name' => 'ajax_document_nomorigine',
                    'class' => 'Docchangements',
                ))*/
               
               
               ->add('OriginalFilename', 'text', array(
                   'label'=>'Nom d\'origine',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                    'mapped' => false, 'required' => false))
             
               
                  ->add('changements_nom', 'genemu_jqueryautocomplete_text', array(
                    'label' => 'Changement(s LIKE)',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                    'configs' => array('minLength' => 2),
                    'mapped' => false, 'required' => false,
                    'route_name' => 'ajax_changements_nom',
                    'class' => 'Docchangements',
                ))
               
                 ->add('idchangements', 'entity', array(
                    'class' => 'ApplicationChangementsBundle:Changements',
                     'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
                    },
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    'required' => false,
                    'label' => 'Changement (=)'
                ))
               ;
            
    }

    public function getName() {
        return 'docchangements_searchfilter';
    }

    /* public function setDefaultOptions(OptionsResolverInterface $resolver) {
      $resolver->setDefaults(array(
      'csrf_protection' => false,
      'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
      ));
      } */
}
