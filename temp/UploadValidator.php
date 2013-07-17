<?php

namespace Application\CertificatsBundle\Entity\Validator;

use Doctrine\ORM\Mapping as ORM;
 
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * Certificats_Files
 *
 * @ORM\Table(name="certificats_files")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */

class UploadValidator  extends ConstraintValidator{
    
     public function validate($value, Constraint $constraint)
    {
        $now = new \DateTime();
        $age = $now->diff($value);
        if($age->y < 16)
        {
            $this->context->addViolation($constraint->message);
             
        }      
    }
}