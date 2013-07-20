<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Application\RelationsBundle\Entity\Projet;
use Application\CentralBundle\Model\DocchangementsBase;


/**
 * CertificatsProjet
 *
 * @Orm\MappedSuperclass
 * @ORM\Table(name="projet_alldocuments")
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\DocumentbbRepository")
 * @ORM\HasLifecycleCallbacks
 */


class Documentbb  {

    
 /**
     * lien vers projets
     *
     *  
     * @ORM\ManyToMany(targetEntity="Projet", mappedBy="picture",cascade={"persist"})
     */
    private $idprojet;
    
    
   /**
     * Date/Time of the update
     *
     * @var \Datetime
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

     protected $disk_path = 'uploads/documents';

    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->idprojet = new ArrayCollection();
    }

   
    /**
* Add idchangement
*
* @param \Application\RelationsBundle\Entity\Projet $idprojets
* @return Documentbb
*/
    public function addIdprojet(Projet $idprojets) {
       // $this->idprojets[] = $idprojets;
        if (!$this->idprojet->contains($idprojets)) {
            if (!$idprojets->getPicture()->contains($this)) {

                $idprojets->addPicture($this); // Lie le Client au produit.
            }
            $this->idprojet->add($idprojets);
        }
        //$this->idchangement[] = $idchangement;
        // return $this;
    }

    /**
     * Get idprojet
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdprojet()
    {
        return $this->idprojet;
    }

   
    
    

    public function setIdprojet($items) {
        if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addIdprojet($item);
            }
        } elseif ($items instanceof Projet) {
            $this->addPicture($items);
        } else {
            throw new \Exception("$items must be an instance of Changements or ArrayCollection");
        }
    }

    /**
     * Remove idprojet
     *
      * @param \Application\RelationsBundle\Entity\Projet $idprojets
     */
    public function removeIdprojet(Projet $idprojets) {
        if (!$this->idprojets->contains($idprojets)) {
            return;
        }
        $this->idprojets->removeElement($idprojets);
        $idprojets->removePicture($this);
    }
}