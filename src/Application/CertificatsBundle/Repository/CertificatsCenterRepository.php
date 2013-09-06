<?php

namespace Application\CertificatsBundle\Repository;

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

    public function myFindxxAll() {
        return $this->createQueryBuilder('a')
                        ->select('a,b')
                        ->add('orderBy', 'a.id DESC')
                        ->leftJoin('a.project', 'b')
                        ->getQuery();

        //->getResult();
    }

    public function myFindaAll($id = null) {
        /* return $this->createQueryBuilder('a')
          ->select('a')
          ->getQuery()->getResult()
          ; */

        $query = $this->createQueryBuilder('a')
                ->select(array('a,b,c,d,e,f'))
                ->leftJoin('a.project', 'b')
                ->leftJoin('a.typeCert', 'c')
                ->leftJoin('a.demandeur', 'd')
                ->leftJoin('a.idEnvironnement', 'e')
                ->leftJoin('a.fichier', 'f')
                ->orderBy('a.id', 'DESC');
        if (isset($id)) {
            $query->andwhere('b.id = :myid');
            $query->setParameter('myid', $id);
        }
        ;
         return $query;
        //->getQuery();
        
        //return $query->getQuery()->getResult();
    }

}
