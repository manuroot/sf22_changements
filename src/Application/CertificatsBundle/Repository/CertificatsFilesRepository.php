<?php

namespace Application\CertificatsBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CertificatsFilesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CertificatsFilesRepository extends EntityRepository {

    public function myFindAll($id = null) {
        //$values = array('a,partial b.{id,fileName,project}');
        $values = array('a,b,c,d');
        $query = $this->createQueryBuilder('a')
                ->select($values)
                ->leftJoin('a.certificats', 'b')
                ->leftJoin('b.project', 'c')
                ->leftJoin('b.typeCert', 'd')
                ->orderBy('a.id', 'DESC');
        if (isset($id)) {
            $query->andwhere('b.id = :myid');
            $query->setParameter('myid', $id);
        }
        return $query;
        //  return $query->getQuery();
    }

    // TODO REGEX
    public function getListBy($criteria) {

       //  var_dump($criteria);exit(1);
        $query = $this->createQueryBuilder('a')
                ->select('a,b,c,d')
                ->add('orderBy', 'a.id DESC')
                ->leftJoin('a.certificats', 'b')
                ->leftJoin('b.project', 'c')
                 ->leftJoin('b.typeCert', 'd');
        $parameters = array();

        /* if (isset($criteria['certificats']) && $criteria['certificats'] != "") {
          //       var_dump($criteria['idEnvironnement']);exit(1);
          $query->andWhere('b.id IN (:idchangements)');
          $query->distinct('GroupConcat(b.nom) AS kak');
          $parameters['certificats'] = $criteria['certificats'];
          } */



        if (isset($criteria['updatedAt']) && $criteria['updatedAt'] != "") {
            $query->andWhere('a.updatedAt > (:updatedAt)');
            $parameters['updatedAt'] = $criteria['updatedAt'];
        }
        if (isset($criteria['updatedAt_max']) && $criteria['updatedAt_max'] != "") {
            $query->andWhere('a.updatedAt < (:updatedAt_max)');
            $parameters['updatedAt_max'] = $criteria['updatedAt_max'];
        }


        if (isset($criteria['project']) && $criteria['project'] != "") {
          //  var_dump($criteria['project']);exit(1);
            $query->andWhere('c.id IN (:project)');
            //  $query->distinct('GroupConcat(d.nomprojet) AS projet');
            $parameters['project'] = $criteria['project'];
        }
   if (isset($criteria['typeCert']) && $criteria['typeCert'] != "") {
          //  var_dump($criteria['project']);exit(1);
            $query->andWhere('d.id IN (:typeCert)');
            //  $query->distinct('GroupConcat(d.nomprojet) AS projet');
            $parameters['typeCert'] = $criteria['typeCert'];
        }

        // Supprimer les autres champs qui ne sont pas dans la classe
        foreach ($criteria as $field => $value) {
            if (!$this->getClassMetadata()->hasField($field)) {
                // Make sure we only use existing fields (avoid any injection)
                unset($criteria[$field]);
                //  continue;
            }
        }


        //les like
        $like_arrays = array('name', 'md5', 'OriginalFilename', 'path', 'certificats');
        foreach ($like_arrays as $val) {
            //  echo "val=$val<br>";
            if (isset($criteria[$val]) && !preg_match('/^\s*$/', $criteria[$val])) {

                //   if (isset($criteria[$val]) && ! preg_match('/[\s]+/',$criteria[$val])) {
                //      echo "critere=" . $criteria["$val"] . "<br>";
                $query->andWhere("a.$val LIKE :$val");

                $parameters[$val] = '%' . $criteria[$val] . '%';
            }
        }

        $query->setParameters($parameters);


        return $query;
    }

    public function findAjaxValue($criteria) {
        $parameters = array();
        $query = $this->createQueryBuilder('a');
        // Supprimer champs qui ne sont pas dans la classe
        foreach ($criteria as $field => $value) {
            if (!$this->getClassMetadata()->hasField($field)) {
                // Make sure we only use existing fields (avoid any injection)
                unset($criteria[$field]);
                //  continue;
            }
        }
        //les like
        $like_arrays = array('name', 'OriginalFilename');
        foreach ($like_arrays as $val) {
            //  echo "val=$val<br>";
            if (isset($criteria[$val]) && !preg_match('/^\s*$/', $criteria[$val])) {
                $query->andWhere("a.$val LIKE :$val");
                $parameters[$val] = '%' . $criteria[$val] . '%';
            }
        }
        $query->setParameters($parameters);
        return $query;
    }

     public function findTypeFile() {
     
    }

}
