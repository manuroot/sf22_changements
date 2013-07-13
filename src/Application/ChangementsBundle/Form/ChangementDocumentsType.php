<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


class ChangementDocumentsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
       
      //  foreach ( $builder->getData()->getId() as $myid){}
        /* if ($builder->getData()->getId()) { 
               $builder->add('file','file',array('label'=>'Fichier','required'=>false));
        }else {
          $builder->add('file','file',array('label'=>'Fichier (*)','required'=>true,));
        }*/
        $factory = $builder->getFormFactory();
    
     $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            // respond to the event, modify data, or form elements
 
            $data = $event->getData();
            $form = $event->getForm();
            foreach ($data->getId() as $myid){
           // if ($data->getId()) {
           //  $form->add($factory->createNamed('file', 'file',array('label'=>'Fichier ()','required'=>false)));
          //  }else {
           //      $form->add($factory->createNamed('file', 'file',array('label'=>'Fichier ()','required'=>true)));
           // }
            //}
            }
        });
        
       /* if ($builder->getData()->getId()) { // or !getId()
               $builder->add('file','file',array('label'=>'Fichier (*)','required'=>false));
        }else {
          $builder->add('file','file',array('label'=>'Fichier ()','required'=>true,));
        }*/
    /*$builder->addEventListener(
            FormEvents::PRE_BIND,
            function(FormEvent $event) use($factory){
                $data = $event->getData();
                $form = $event->getForm();
                if ($data->getId()){
                     $form->add('file','file',array('required'=>false));
                }
                else {  $form->add('file','file',array('required'=>true));}
                
            }
        );*/
       /* if ($builder->getData()->isNew()) { // or !getId()
               $builder->add('file','file',array('required'=>true,));
        }else {
          $builder->add('file','file',array('required'=>false,));
        }*/
               // ->add('name')
          $builder->add('file',null,array('label'=>'Fichier ()'));
           $builder->add('name','text',array(
               'label' => 'Description du Fichier',
               'required'=>false,
               )
               );             
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            //    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'data_class' => 'Application\ChangementsBundle\Entity\Docchangements',
                // 'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'doc';
    }

}