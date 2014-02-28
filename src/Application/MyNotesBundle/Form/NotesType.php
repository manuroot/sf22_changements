<?php

namespace Application\MyNotesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotesType extends AbstractType
{
     /* private $Username;

public function __construct($username = null) {

$this->Username = $username;
}*/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('text')
                 ->add('text', 'textarea', array(
                    'label' => 'Resumé du Post',
                    'attr' => array(
                        'cols' => "60",
                        'rows' => "10",
                        'class' => 'tinymce',
                        )))
            ->add('name')
            ->add('color');
          /* ->add('xyz')
->add('dt')
->add('wh')*/
          // ->add('proprietaire')
                
         
             // ->add('classement')
            
        //,null, array('label'=>'toto'));
     // ;
        /*$builder->add('color','entity', array(
'class' => 'Application\MyNotesBundle\Entity\NotesColor',
'property' => 'nom',
'multiple' => true,
'required' => true,
'label' => 'Couleur',
)); */
           $builder->add('categories','entity', array(
            'class' => 'Application\MyNotesBundle\Entity\NotesCategories',
            'property' => 'nom',
            'multiple' => false,
            'required' => true,
            'label' => 'macategorie'
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\MyNotesBundle\Entity\Notes'
        ));
    }

    public function getName()
    {
        return 'application_mynotesbundle_notestype';
    }
}


