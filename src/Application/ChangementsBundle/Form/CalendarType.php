<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CalendarType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->setAttribute('show_legend', false); // no legend for main form
       /*
     
    }
        */
        $min_year=Date('Y')-3;
        $max_year=Date('Y')+3;
$builder
   ->add('publishedAt', 'date', array(
                            'widget' => 'choice',
                            'format' => 'yyyy-MMMM-dd',
                            'pattern' => '{{ year }}-{{ month }}-{{ day }}',
                            'years' => range($min_year,$max_year),
                            'label' => false,
                            'input' => 'string',
       'mapped'=>false
                  
                        ))
            ;
         
    }

    public function getName() {
        return 'changements_calendar_form';
    }

}
