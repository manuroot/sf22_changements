<?php

namespace Application\ChangementsBundle\Manager;

abstract class ChangementsBaseManager
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