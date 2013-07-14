<?php

namespace Application\CertificatsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CertificatsCenterRepository extends EntityRepository {

    public function myFindAll() {
        return $this->createQueryBuilder('a')
                        ->getQuery();

        //->getResult();
    }

    public function myFindaAll($id=null) {
       
      //  $id=2;
        $query=$this->createQueryBuilder('a')
                ->select(array('a,b,c,d,e,f'))
                
                        ->leftJoin('a.project', 'b')
                        ->leftJoin('a.typeCert', 'c')
                ->leftJoin('a.demandeur', 'd')
                 ->leftJoin('a.idEnvironnement', 'e')
                ->leftJoin('a.fichier', 'f')
                        ->orderBy('a.id', 'DESC') ;
        if (isset($id)){
               $query->andwhere('b.id = :myid');
             $query->setParameter('myid', $id);
        }
                    return $query;
   //  return $query->getQuery();
    }
    
    /*public function myFindaAll() {
        return $this->createQueryBuilder('a')
                ->select(array('a,b,c'))
               
                        ->leftJoin('a.project', 'b')
                        ->leftJoin('a.typeCert', 'c')
                        ->orderBy('a.id', 'DESC') ;
                        //->getQuery();
    }*/

     

}
