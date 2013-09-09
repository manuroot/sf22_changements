<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\CertificatsBundle\Form;

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

class CertificatsCenterFiltresType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
             
                
                ->add('fileName', 'filter_text', array(
                    'label'=>'Nom du fichier',
                        'text_options'=>array( 'attr' => array('icon' => 'icon-user')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    ))
                
                    
                ->add('serviceName', 'filter_text', array(
                          'label'=>'Service',
                        'text_options'=>array( 'attr' => array('icon' => 'icon-wrench')),
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    ))
                ->add('cnName', 'filter_text', array(
                           'label'=>'CN du certificat',
                       'text_options'=>array( 'attr' => array('icon' => 'icon-wrench')),
                     'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                  ->add('description', 'filter_text', array(
                           'text_options'=>array( 'attr' => array('icon' => 'icon-wrench')),
               
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                
                
                ->add('endTime', 'filter_date_range', array(
                    'label' => 'Date début',
                    'left_date_options' => array(
                             'attr' => array('placeholder' => '> date Fin',
                                 'style'=>'width:150px'),
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                    'right_date_options' => array(
                            'attr' => array('placeholder' => '< date Fin','style'=>'width:150px'),
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                ))
                
               
                ->add('project', 'filter_entity', array(
                     'label'=>'Projets',
                    'class' => 'Application\RelationsBundle\Entity\Projet',
                     'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.nomprojet', 'ASC');
            },
                    'property' => 'nomprojet',
                    'expanded' => false,
                    'multiple' => false,
                ))
                  
                   ->add('idEnvironnement', 'entity', array(
                       'label'=>'Environnements',
                    'class' => 'Application\RelationsBundle\Entity\Environnements',
                     'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
            },
                    'property' => 'nom',
                    'expanded' => false,
                    'multiple' => false,
                    
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
                /*
                  $builder->add('foo', 'filter_text', array(
                  'condition_pattern' => TextFilterType::SELECT_PATTERN,
                  ));
                  $builder->add('enabled', $this->checkbox ? 'filter_checkbox' : 'filter_boolean');
                  $builder->add('createdAt', $this->datetime ? 'filter_datetime' : 'filter_date');;
                 */
                //TextFilterType::PATTERN_*
                /*     'attr' => array('placeholder' => '> date Fin','style'=>'width:150px'),
                        'widget' => 'single_text'
                */    
                    ->add('port', 'filter_text', array(
                            'text_options'=>array( 
                                'attr' => array(
                                    'style'=>'width:120px',
                                'icon' => 'icon-briefcase')),
            /*   'attr' => array( 'style'=>'width:120px'),*/
                     'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                    
               
                   
                //   ->add('port', 'filter_text')
                // ->add('startDate')
                //  ->add('endTime')
                // ->add('addedDate')
                ->add('serverName', 'filter_text', array(
                            'text_options'=>array( 'attr' => array('icon' => 'icon-briefcase')),
               
                     'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('typeCert', 'filter_entity', array(
                    'class' => 'Application\RelationsBundle\Entity\Filetype',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.fileType', 'ASC');
            },
                    'property' => 'fileType',
                    'expanded' => false,
                    'multiple' => false,
        ));
      /*  $builder->add('typeCert', 'entity', array(
            'class' => 'Application\RelationsBundle\Entity\Filetype',
            'query_builder' => function(EntityRepository $em) {
                return $em->createQueryBuilder('u')
                                ->orderBy('u.fileType', 'ASC');
            },
            'property' => 'FileType',
            'multiple' => false,
            'required' => true,
            'label' => 'Type',
            'empty_value' => '--- Choisir une option ---'
        ));*/
         /*       ->add('company', 'filter_text', array(
    'apply_filter' => function (QueryBuilder $queryBuilder, Expr $expr, $field, array $values) {
        if (!empty($values['value'])) {
            // add the join if you need it and it not already added
            // $queryBuilder->leftJoin('u.company', 'c');

            $queryBuilder->andWhere('c.name = :name')
                ->setParameter('name', $values['value']);
        }
    },
));;*/



       /* $listener = function(FormEvent $event) {
                    // Is data empty?
                    foreach ($event->getData() as $data) {
                        if (is_array($data)) {
                            print_r($data);
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
        $builder->addEventListener(FormEvents::POST_BIND, $listener);*/
    }

    public function getName() {
        return 'certificats_filter';
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
