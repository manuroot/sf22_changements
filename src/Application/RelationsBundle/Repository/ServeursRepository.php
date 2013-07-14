<?php

namespace Application\RelationsBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ServeursRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ServeursRepository extends EntityRepository
{
    
      public function myFindAll($id=null) {
        $query=$this->createQueryBuilder('a')
                  ->select('a,b,c,d')
                        ->leftJoin('a.idzone', 'b')
                        ->leftJoin('a.idsite', 'c')
                        ->leftJoin('a.id_env', 'd')
               /*    ->addSelect('g')
                //->addSelect('g')
                ->distinct('GroupConcat(g.nom) AS kak')
                ->leftJoin('a.idEnv', 'g')*/
            
                      /*  ->leftJoin('a.idStatus', 'd')
                        ->leftJoin('a.picture', 'f')*/
                        ->orderBy('a.id');
         if (isset($id)){
               $query->andwhere('a.id = :myid');
             $query->setParameter('myid', $id);
              return $query->getQuery()->getSingleResult();
        }
         return $query;
        //->getQuery();
                       

        //->getResult();
    }
}
 /*public function myFindaAll($id=null) {
       
        $id=2;
        $query=$this->createQueryBuilder('a')
                ->select(array('a,b,c'))
               
                        ->leftJoin('a.project', 'b')
                        ->leftJoin('a.typeCert', 'c')
                        ->orderBy('a.id', 'DESC') ;
        if (isset($id)){
               $query->andwhere('b.id = :myid');
             $query->setParameter('myid', $id);
        }
                return $query->getQuery();
    }*/