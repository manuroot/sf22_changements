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
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityRepository;

//use Doctrine\ORM\EntityRepository;

class ServeursFiltresType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
                ->add('nom', 'filter_text', array(
                    'text_options' => array('attr' => array('icon' => 'icon-user')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                ))
                ->add('description', 'filter_text', array(
                    'label' => 'Description',
                    'text_options' => array('attr' => array('icon' => 'icon-wrench')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('ip_in', 'filter_text', array(
                    'label' => 'IP In',
                    'text_options' => array('attr' => array('icon' => 'icon-wrench')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('nom_dns', 'filter_text', array(
                    'label' => 'Nom DNS',
                    'text_options' => array('attr' => array('icon' => 'icon-user')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                ))
                ->add('ip_out', 'filter_text', array(
                    'label' => 'IP Out',
                    'text_options' => array('attr' => array('icon' => 'icon-wrench')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('idsite', 'filter_entity', array(
                    'label' => 'Site',
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
                    'label' => 'Zone',
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
                    'label' => 'Environnement',
                    'class' => 'Application\RelationsBundle\Entity\Environnements',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
                    },
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                ))
               /* ->add('idProjet', 'entity', array(
                    'label' => 'Projet',
                    'class' => 'Application\RelationsBundle\Entity\Projet',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nomprojet', 'ASC');
                    },
                    'property' => 'nomprojet',
                    'expanded' => false,
                    'multiple' => false,
                    'required'=>false,
                ))*/
        ;
                    
           /* ->add('idProjet', 'filter_entity', array(
                 'label' => 'Projet',
                    'class' => 'Application\RelationsBundle\Entity\Projet',
           'apply_filter' => function(QueryInterface $filterQuery, $field, $values) {
                if (!empty($values['value'])) {
                    $qb = $filterQuery->getQueryBuilder();
               
                    $qb->leftJoin('u.idProjet', 'e');
                    $qb->andWhere($filterQuery->getExpr()->in('e.nomprojet', $values['value']));
                    
                }
            },
        ));*/
                    /* $builder->add('my_number_field', 'filter_number', array(
            'apply_filter' => function(QueryInterface $filterQuery, $field, $values) {
                if (!empty($values['value'])) {
                    $qb = $filterQuery->getQueryBuilder();
                    $qb->andWhere($filterQuery->getExpr()->eq($field, $values['value']));
                }
            },
        ));*/
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
