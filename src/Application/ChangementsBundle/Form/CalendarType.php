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
$builder
   ->add('publishedAt', 'birthday', array(
                            'widget' => 'choice',
                            'format' => 'yyyy-MM-dd',
                            'pattern' => '{{ year }}-{{ month }}-{{ day }}',
                            'years' => range(Date('Y'), 2008),
                            'label' => false,
                            'input' => 'string',
       'mapped'=>false
                  
                        ));
         
    }

   /* public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
       'data_class' => 'Application\ChangementsBundle\Entity\Changements',
            'cascade_validation' => true,
        ));
    }*/

    public function getName() {
        return 'changements_calendar_form';
    }

}
