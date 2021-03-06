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

     public function getProjetsForRequeteBuilder() {
        $query = $this->createQueryBuilder('a')
                ->select('distinct b.id,b.nomprojet')
                //   ->from('Application\ChangementsBundle\Entity\Changements', 'a')
                ->leftJoin('a.project', 'b')
                ->groupBy('b.id')
                ->add('orderBy', 'b.nomprojet ASC');

        $choices = array();
        $arr = $query->getQuery()->getArrayResult();
        foreach ($arr as $result) {
            $choices[$result['id']] = $result['nomprojet'];
        }
        return $choices;
    }
    //$typeCert
 public function getTypeCertForRequeteBuilder() {
        $query = $this->createQueryBuilder('a')
                ->select('distinct b.id,b.fileType')
                //   ->from('Application\ChangementsBundle\Entity\Changements', 'a')
                ->leftJoin('a.typeCert', 'b')
                ->groupBy('b.id')
                ->add('orderBy', 'b.fileType ASC');

        $choices = array();
        $arr = $query->getQuery()->getArrayResult();
        foreach ($arr as $result) {
            $choices[$result['id']] = $result['fileType'];
        }
        return $choices;
    }
}
