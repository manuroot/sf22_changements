<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Application\ChangementsBundle\Entity\Changements;
use Application\CentralBundle\Model\DocchangementsBase;


/**
 * Projet
 * @Orm\MappedSuperclass
 * @ORM\Table(name="changements_fichiers")
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Entity\DocchangementsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Docchangements  extends DocchangementsBase {

    
        /**
     *
     * @var ArrayCollection Projet $idchangements
     *
     * Inverse Side
     * 
     * @ORM\ManyToMany(targetEntity="Changements", mappedBy="picture",cascade={"persist"})
     */
    protected $idchangement;


    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->idchangement = new ArrayCollection();
    }

    /**
     * Add idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     * @return Docchangements
     */
    public function addIdchangement(Changements $idchangement) {
        if (!$this->idchangement->contains($idchangement)) {
            if (!$idchangement->getPicture()->contains($this)) {

                $idchangement->addPicture($this);  // Lie le Client au produit.
            }
            $this->idchangement->add($idchangement);
        }
        //$this->idchangement[] = $idchangement;
        //  return $this;
    }

    public function setIdchangement($items) {
        if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addIdchangement($item);
            }
        } elseif ($items instanceof Changements) {
            $this->addPicture($items);
        } else {
            throw new \Exception("$items must be an instance of Changements or ArrayCollection");
        }
    }

    /**
     * Remove idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     */
    public function removeIdchangement(Changements $idchangement) {
        if (!$this->idchangement->contains($idchangement)) {
            return;
        }
        $this->idchangement->removeElement($idchangement);
        $idchangement->removePicture($this);
    }

    /**
     * Get idchangement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdchangement() {
        return $this->idchangement;
    }


}