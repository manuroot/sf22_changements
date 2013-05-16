<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Environnement
 *
 * @ORM\Table(name="changements_status")
 * @ORM\Entity
 */
class ChangementsStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=40, nullable=false)
     */
    private $nom;

     /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;
    
    /**
    * @ORM\ManyToMany(targetEntity="Changements", mappedBy="idEnvironnement")
    */
    private $idchangements;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomProjet
     *
     * @param string $nom
     * @return Environnement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

     public function __toString() {
        return $this->getNom();    // this will not look good if SonataAdminBundle uses this ;)
    }

    /**
     * Get nomapplis
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

     /**
     * Set description
     *
     * @param string $description
     * @return Environnement
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

   

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idchangements = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add idchangements
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangements
     * @return Environnements
     */
    public function addIdchangement(\Application\ChangementsBundle\Entity\Changements $idchangements)
    {
        $this->idchangements[] = $idchangements;
    
        return $this;
    }

    /**
     * Remove idchangements
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangements
     */
    public function removeIdchangement(\Application\ChangementsBundle\Entity\Changements $idchangements)
    {
        $this->idchangements->removeElement($idchangements);
    }

    /**
     * Get idchangements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdchangements()
    {
        return $this->idchangements;
    }
}