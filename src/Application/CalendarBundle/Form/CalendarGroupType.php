<?php

namespace Application\CalendarBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
//use Symfony\Component\Form\FormEvents;
//use Symfony\Component\Form\FormEvent;

class CalendarGroupType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             //->add('owner','entity')
                
                 ->add('owner', 'entity', array(
                    'class' => 'ApplicationSonataUserBundle:User',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.username', 'ASC');
                    },
                    'property' => 'username',
                    'multiple' => false,
                    'required' => false,
                    'label' => 'Username',
                    'empty_value' => '--- Choisir une option ---'
                ))
            
            ->add('nomGroup',null,array('label'=>'Nom du groupe'))
            ->add('description',null,array('label'=>'Description'))
            ->add('email',null,array('label'=>'Email du groupe'))
            ->add('users',null,array('label'=>'Utilisateurs'))
          
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\CalendarBundle\Entity\CalendarGroup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'application_calendarbundle_calendargroup';
    }
}
