<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class TestGenemuType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->setAttribute('show_legend', false); // no legend for main form
        // $child = $builder->create('user', new SomeSubFormType(), array('show_child_legend' => true)); // but legend for this subform
        //  $builder->add($child);
        $builder
                //   ->add('nom')
             

                
                ->add('soccer_player', 'genemu_jqueryautocomplete_text', array(
                    'mapped'=>false,
            'suggestions' => array(
                'Ozil',
                'Van Persie'
            ),
        ))
                 ->add('sscountry', 'genemu_jqueryselect2_country', array('mapped'=>false))
                ->add('country', 'genemu_jqueryautocompleter_country',array(
                    'mapped'=>false));
           
    
                    
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Application\ChangementsBundle\Entity\Changements',
            'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'changements';
    }

}
