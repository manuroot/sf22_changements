<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\QueryBuilder;
use Application\ChangementsBundle\Repository\ChangementsRepository;
use Application\ChangementsBundle\Entity\Changements;
use Application\ChangementsBundle\Entity\ChangementsStatus;
use Application\RelationsBundle\Entity\Projet;
use Application\RelationsBundle\Repository\ProjetRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ChangementsStatusType extends AbstractType {

  

    public function buildForm(FormBuilderInterface $builder, array $options) {

 
      
         /*   $builder->add('inlineCheckboxes', 'choice', array(
    'multiple' => true,
    'expanded' => true,
    'choices' => array(1, 2, 3),
    'attr' => array('inline' => true)
    ))*/
               $builder->add('idStatus', 'entity', array(
                    'label' => 'Status',
                    'class' => 'Application\ChangementsBundle\Entity\ChangementsStatus',
                    'property' => 'nom',
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false,
                    'attr'      => array('class' => 'toto'),
                        /* 'empty_value' => '--- Choisir une option ---', */
                ));
            
    }

    public function getName() {
        return 'changements_searchstatus';
    }

   
}
