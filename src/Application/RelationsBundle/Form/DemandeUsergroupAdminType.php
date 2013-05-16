<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;

class DemandeUsergroupAdminType extends AbstractType
{
    
    /*  private $UserId;
    private $ProduitId;*/

    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    
    /*
    public function __construct($userid = null, $produitid = null) {

    /
        $this->UserId = $userid;
        $this->ProduitId = $produitid;
        if ( $this->get('security.context')->isGranted('ROLE_ADMIN')) { 
            
        //User is logged in    
}  
    }*/
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->securityContext->getToken()->getUser();
        if (!$user) {
            throw new \LogicException(
                'Le user n\' est pas connecté !'
            );
        }
        $user_id=$user->getId();
         $builder
        ->add('name')
            ->add('description');
        if ( $this->securityContext->isGranted('ROLE_ADMIN')) { 
            
             $builder
             ->add('isaccepted')
             //  ->add('idgroup')
            ->add('idgroup')
            ->add('iduser')
        ;
        }
        else {
        $builder
       
            ->add('isaccepted',null,array('read_only'=>true))
             //  ->add('idgroup')
            ->add('idgroup',null,array('read_only'=>true))
            ->add('iduser', 'entity', array(
                //'class' => 'Application\EservicesBundle\Entity\CertificatsProjet',
                'class' => 'ApplicationSonataUserBundle:User',
                'query_builder' => function(EntityRepository $em) use ($user_id) {
                    return $em->createQueryBuilder('u')
                                    //    ->leftJoin('u.produit', 'a')
                                    //  ->leftJoin('a.proprietaire', 'v')
                                    ->where('u.id = :user')
                                    ->setParameter('user', $user_id)
                                    ->orderBy('u.username', 'ASC');
                },
                'property' => 'username',
                'multiple' => false,
                'required' => true,
                        'read_only'=>true,
                'label' => 'Username',
            //    'empty_value' => '--- Choisir une option ---'
            ))
                
                
                
           
               // ->add('iduser', 'text', array('data' => $id_user, 'read_only' => true))

         //   ->add('iduser')
        ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\DemandeUsergroup'
        ));
    }

    public function getName()
    {
        return 'application_relationsbundle_demandeusergrouptype';
    }
}
