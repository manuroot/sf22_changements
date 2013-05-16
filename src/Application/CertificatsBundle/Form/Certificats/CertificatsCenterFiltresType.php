<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\CertificatsBundle\Form\Certificats;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType as TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;

//use Doctrine\ORM\EntityRepository;

class CertificatsCenterFiltresType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
                ->add('fileName', 'filter_text', array(
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('cnName', 'filter_text', array(
                     'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                ->add('endTime', 'filter_date_range', array(
                     'label' => 'Date de Fin',
                    'left_date' => array(
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                    'right_date' => array(
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                ))
                 ->add('project', 'filter_entity', array(
                'class' => 'Application\RelationsBundle\Entity\Projet',
                'property' => 'nomprojet'
            ))
        /*
                 $builder->add('foo', 'filter_text', array(
            'condition_pattern' => TextFilterType::SELECT_PATTERN,
        ));
        $builder->add('enabled', $this->checkbox ? 'filter_checkbox' : 'filter_boolean');
        $builder->add('createdAt', $this->datetime ? 'filter_datetime' : 'filter_date');;
   */ 
                //TextFilterType::PATTERN_*
                ->add('port', 'filter_text', array(
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
                     'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                
                    ->add('typeCert', 'filter_entity', array(
                'class' => 'Application\RelationsBundle\Entity\Filetype',
                'property' => 'fileType'
            ));
                 
  
     
     
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
