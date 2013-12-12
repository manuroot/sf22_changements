<?php

namespace Application\CalendarBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Application\CalendarBundle\Form\EventListener\AddFichierFieldSubscriber;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


class CalendarDocumentsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
          $builder->addEventSubscriber(new AddFichierFieldSubscriber());
           $builder->add('name','text',array(
               'label' => 'Description du Fichier',
               'required'=>false,
               )
               );
             
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            //    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'data_class' => 'Application\CalendarBundle\Entity\DocCalendar',
                // 'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'doc_cal';
    }

}