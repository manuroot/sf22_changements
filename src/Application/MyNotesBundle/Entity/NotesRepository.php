<?php

namespace Application\MyNotesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
* NotesRepository
*
* This class was generated by the Doctrine ORM. Add your own custom
* repository methods below.
*/
class NotesRepository extends EntityRepository
{
    
    public function myFindAll() {
        return $this->createQueryBuilder('a')
                        ->getQuery();
                        
        //->getResult();
    }
    
      public function myFindaAll() {
        return $this->createQueryBuilder('a')
                        ->leftJoin('a.categories', 'b')
                        ->leftJoin('a.color', 'c')
                ->leftJoin('a.proprietaire', 'd')
                        ->getQuery();
    }
    
   
    
     public function myFindamoi($user_id) {
       $query = $this->createQueryBuilder('a')
                ->add('orderBy', 'a.id DESC')
                ->where('a.proprietaire = :proprietaire')
                ->leftJoin('a.proprietaire', 'b')
                 ->setParameter('proprietaire', $user_id)
                ->getQuery();

        return $query;

       
    }
}