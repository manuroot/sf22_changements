<?php

namespace Application\ChangementsBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\TextFilterType as TextFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Doctrine\ORM\EntityRepository;

class ChangementsFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
                ->add('nom', 'filter_text', array(
                    'condition_pattern' => FilterOperands::OPERAND_SELECTOR,
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),))
                 ->add('dateDebut', 'filter_date_range', array(
                     'label' => 'Date début',
                    'left_date' => array(
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                    'right_date' => array(
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                ))
                 ->add('dateFin', 'filter_date_range', array(
                     'label' => 'Date début',
                    'left_date' => array(
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                    'right_date' => array(
                        'widget' => 'single_text'
                    /* 'time_widget' => 'single_text' */
                    ),
                ))
                 ->add('idProjet', 'filter_entity', array(
                'class' => 'Application\RelationsBundle\Entity\Projet',
                'property' => 'nomprojet'
            ))
                         ->add('demandeur', 'filter_entity', array(
                'class' => 'Application\RelationsBundle\Entity\ChronoUser',
                'property' => 'nomUser'
            ));
                 
  
     
     
    }

    public function getName() {
        return 'changements_filter';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
