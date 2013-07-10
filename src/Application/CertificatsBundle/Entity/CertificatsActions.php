<?php

namespace Application\CertificatsBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Filetype
 *
 * @ORM\Table(name="certificats_actions")
 * * @ORM\Entity(repositoryClass="Application\CertificatsBundle\Repository\CertificatsActionsRepository")
 * @ORM\Entity
 */
class CertificatsActions
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
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     */
    private $nom;

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
     * Set fileType
     *
     * @param string $nom
     * @return nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get fileType
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    
 public function __toString() {
        return $this->getNom();    // this will not look good if SonataAdminBundle uses this ;)
    }
    
}