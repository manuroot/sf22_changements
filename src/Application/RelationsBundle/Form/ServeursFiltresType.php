<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\RelationsBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType as TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityRepository;

//use Doctrine\ORM\EntityRepository;

class ServeursFiltresType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
             
                
                ->add('nom', 'filter_text', array(
                        'text_options'=>array( 'attr' => array('icon' => 'icon-user')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    ))
                ->add('description', 'filter_text', array(
                           'text_options'=>array( 'attr' => array('icon' => 'icon-wrench')),
               
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                 ->add('ip_in', 'filter_text', array(
                           'text_options'=>array( 'attr' => array('icon' => 'icon-wrench')),
               
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                 ->add('nom_site', 'filter_text', array(
                        'text_options'=>array( 'attr' => array('icon' => 'icon-user')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    ))
                 ->add('nom_dns', 'filter_text', array(
                        'text_options'=>array( 'attr' => array('icon' => 'icon-user')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    ))
                
                 ->add('ip_out', 'filter_text', array(
                           'text_options'=>array( 'attr' => array('icon' => 'icon-wrench')),
               
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                
               ->add('idsite', 'filter_entity', array(
                    'class' => 'Application\RelationsBundle\Entity\ServeursSites',
                     'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
            },
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                ))
                ->add('idzone', 'filter_entity', array(
                    'class' => 'Application\RelationsBundle\Entity\ServeursZones',
                     'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
            },
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                )) 
                ->add('id_env', 'filter_entity', array(
                    'class' => 'Application\RelationsBundle\Entity\Environnements',
                     'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
            },
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                ))
                    ;
           
    }

    public function getName() {
        return 'serveurs_filter';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }

    /*
      public function getDefaultOptions(array $options)
      {
      return array(
      'validation_groups' => array('no_validation')
      );
      }

      public function getName()
      {
      return 'team_filter';
      } */
}
