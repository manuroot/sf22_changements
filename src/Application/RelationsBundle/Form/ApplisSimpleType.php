<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\RelationsBundle\Form\CertificatsProjetType;

class ApplisSimpleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('nomapplis', null, array(
                    'label'=>'Nom',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )))
                
                 ->add('description', null, array(
                    'label'=>'Description',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        )))
                ;
          //  ->add('nomapplis','text',array('label' => 'Application'))
           // ->add('','text',array('label' => 'Description'))
               /* ->add('save', 'submit', array(
                    'label'=>'Nom',
                    ))*/
       //  ->add('save', 'submit')
    //->add('save_and_add', 'submit');
       
    }
  
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\Applis'
        ));
    }

    public function getName()
    {
        return 'Formulaire_Application_Simple';
    }
}
