<?php

namespace Application\RelationsBundle\Manager;

abstract class ChronoAbsencesBaseManager
{
    protected function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
     protected function removeAndFlush($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
  
   
}