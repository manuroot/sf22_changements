<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Applis
 *
 * @ORM\Table(name="certificats_applis")
 * @ORM\Entity
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
     * @ORM\ManyToMany(targetEntity="Projet", mappedBy="idapplis",cascade={"persist"})
     */
    private $idprojets;

    // @ORM\ManyToMany(targetEntity="Projet", mappedBy="idapplis")

    public function __construct() {
        $this->idprojets = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add idprojets
     *
     * @param \Application\RelationsBundle\Entity\Projet $idprojets
     * @return Applis
     */
    public function addIdprojet(\Application\RelationsBundle\Entity\Projet $idprojets) {
        //   $idprojets->addIdapplis($this);
        //   $this->idprojets[] = $idprojets;
        //   return $this;
        //echo "here";exit;
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if ($this->idprojets->contains($idprojets)) {
            return;
        }
        //sinon
        $this->idprojets->add($idprojets);
        $idprojets->addIdapplis($this);
        //$user->addUserGroup($this);
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