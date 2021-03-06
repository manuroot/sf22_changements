<?php

namespace Application\ChangementsBundle\Repository;
use Doctrine\ORM\EntityRepository;
/**
 * NotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChangementsCommentsRepository extends EntityRepository{
//class ChangementsRepository extends EntityRepository{
 //use EventRepositoryTrait;
    public function myFindaAll() {
        return $this->createQueryBuilder('a')
                ->orderBy('id')
                        ->getQuery();

        //->getResult();
    }

    public function myFindAll() {
        return $this->createQueryBuilder('a')
                        ->leftJoin('a.idProjet', 'b')
                        ->leftJoin('a.idStatus', 'd')
                        ->leftJoin('a.demandeur', 'c')
                 ->leftJoin('a.idusers', 'e')
             //   ->groupby('e.nomUser')
                 ->add('orderBy', 'a.id DESC')
                        //   ->leftJoin('a.demandeur', 'c')
                        ->getQuery();
    }
    
public function getCommentsForChangement($changementId)
    {
        $qb = $this->createQueryBuilder('a')
                   ->select('a,c')
                 ->leftJoin('a.changement','c')
                   ->where('c.id = :changement_id')
                    ->addOrderBy('a.created')
                   ->setParameter('changement_id', $changementId);

        
        return $qb->getQuery()
                  ->getResult();
    }
    
    
       public function myFindaIdAll($id) {
      
         $query = $this->createQueryBuilder('a')
               ->select('a,b')
               ->leftJoin('a.changement','b');
            
         $query->add('orderBy', 'a.id DESC')
                ->andwhere('a.id = :myid');
        $query->setParameter('myid', $id);
               return $query->getQuery()->getSingleResult();
    }

}

