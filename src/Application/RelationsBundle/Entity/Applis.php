<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\RelationsBundle\Entity\Projet;
/**
 * Applis
 *
 * @ORM\Table(name="applis_main")
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\ApplisRepository")
 */
class Applis {

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
     * @ORM\Column(name="nomapplis", type="string", length=40, nullable=false)
     */
    private $nomapplis;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

    /**
     *
     * @var ArrayCollection Projet $idprojets
     *
     * Inverse Side
     * 
     * @ORM\ManyToMany(targetEntity="Projet", mappedBy="idapplis",cascade={"persist"})
     */
    private $idprojets;

    // @ORM\ManyToMany(targetEntity="Projet", mappedBy="idapplis")

    
    
    
    public function __construct() {
        $this->idprojets = new ArrayCollection();
    }

    public function __toString() {
        return $this->getNomapplis();    // this will not look good if SonataAdminBundle uses this ;)
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nomProjet
     *
     * @param string $nomProjet
     * @return Applis
     */
    public function setNomapplis($nomapplis) {
        $this->nomapplis = $nomapplis;

        return $this;
    }

    /**
     * Get nomapplis
     *
     * @return string 
     */
    public function getNomapplis() {
        return $this->nomapplis;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Applis
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    
    /**
     * Add Client
     *
     * @param Client $client
     */
    public function addIdprojet(Projet $idprojets)
    {
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if (!$this->idprojets->contains($idprojets)) {
        if (!$idprojets->getIdapplis()->contains($this)) {
            // ajout coté proprietaire
                $idprojets->addIdappli($this);  // Lie le Client au produit.
            }
            $this->idprojets->add($idprojets);
        }
    }
    public function setIdprojets($items)
    {
        if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addIdprojet($item);
            }
        } elseif ($items instanceof Projet) {
            $this->addIdprojet($items);
        } else {
            throw new \Exception("$items must be an instance of Client or ArrayCollection");
        }
    }
    
   
    /**
     * Remove idprojets
     *
     * @param \Application\RelationsBundle\Entity\Projet $idprojets
     */
    public function removeIdprojet(\Application\RelationsBundle\Entity\Projet $idprojets) {
        if (!$this->idprojets->contains($idprojets)) {
            return;
        }
        $this->idprojets->removeElement($idprojets);
        $idprojets->removeIdappli($this);
    }

    /**
     * Get idprojets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdprojets() {
        return $this->idprojets;
    }

}