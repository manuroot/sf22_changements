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
use Application\ChangementsBundle\Entity\ChangementsRepository;
use Application\ChangementsBundle\Entity\Changements;
use Application\ChangementsBundle\Entity\ChangementsStatus;
use Application\RelationsBundle\Entity\Projet;
use Application\RelationsBundle\Repository\ProjetRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ChangementsStatusType extends AbstractType {

  

    public function buildForm(FormBuilderInterface $builder, array $options) {

 
        $builder
        
                ->add('idStatus', 'filter_entity', array(
                    'label' => 'Status',
                    'class' => 'Application\ChangementsBundle\Entity\ChangementsStatus',
                    'property' => 'nom',
                    'expanded' => true,
                    'multiple' => false,
                    'required' => false,
                        /* 'empty_value' => '--- Choisir une option ---', */
                ));
            
    }

    public function getName() {
        return 'changements_searchstatus';
    }

    /* public function setDefaultOptions(OptionsResolverInterface $resolver) {
      $resolver->setDefaults(array(
      'csrf_protection' => false,
      'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
      ));
      }
     * 
     * SELECT DISTINCT p1_.id AS id0, p1_.nomprojet AS nomprojet1
      FROM changements_main c0_
      LEFT JOIN projet_main p1_ ON c0_.id_projet = p1_.id
     */
}
