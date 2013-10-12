<?php


namespace Application\ChangementsBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;


/**
 * ChronoUser
 *
 * @ORM\Table(name="changements_contact")
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Repository\ChangementsContactRepository")
 * @UniqueEntity(fields="nomUser", message="Ce nom existe déjà...")
 */

class ChangementsContact
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
     * @var string $nomUser
     * @Assert\NotBlank(message="Ce champs ne peut etre vide")
     * @Assert\Length(
     *      min = "4",
     *      max = "30",
     *      minMessage = "the name must be at least {{ limit }} characters length |
     *  Au minimum {{ limit }} caracteres",
     *  maxMessage = "Your first name cannot be longer than than {{ limit }} characters length |
     *  Au maximum {{ limit }} caracteres"
     * )
     *
     * @ORM\Column(name="nom_user", type="string", length=20, nullable=false,unique=true)
     */
    private $nomUser;

    /**
     * @var string
     *
     * @ORM\Column(name="infos", type="string", length=50, nullable=true)
     */
    private $infos;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=16, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="Bureau", type="string", length=30, nullable=true)
     */
   
    
    private $email;
    
  /**
    * @ORM\ManyToMany(targetEntity="Application\ChangementsBundle\Entity\Changements", mappedBy="idusers")
    */
    private $idchangement;

       public function __toString() {
        return $this->getNomUser();    // this will not look good if SonataAdminBundle uses this ;)
    }
    
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
     * Set nomUser
     *
     * @param string $nomUser
     * @return ChronoUser
     */
    public function setNomUser($nomUser)
    {
        $this->nomUser = $nomUser;
    
        return $this;
    }

    /**
     * Get nomUser
     *
     * @return string 
     */
    public function getNomUser()
    {
        return $this->nomUser;
    }

  
    /**
     * Set infos
     *
     * @param string $infos
     * @return ChronoUser
     */
    public function setInfos($infos)
    {
        $this->infos = $infos;
    
        return $this;
    }

    /**
     * Get infos
     *
     * @return string 
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return ChronoUser
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    
        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

  

    /**
     * Set email
     *
     * @param string $email
     * @return ChronoUser
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idchangement = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     * @return ChronoUser
     */
    public function addIdchangement(\Application\ChangementsBundle\Entity\Changements $idchangement)
    {
        $this->idchangement[] = $idchangement;
    
        return $this;
    }

    /**
     * Remove idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     */
    public function removeIdchangement(\Application\ChangementsBundle\Entity\Changements $idchangement)
    {
        $this->idchangement->removeElement($idchangement);
    }

    /**
     * Get idchangement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdchangement()
    {
        return $this->idchangement;
    }

    /**
     * Set idusers
     *
     * @param \Application\RelationsBundle\Entity\ChronoUsergroup $idusers
     * @return ChronoUser
     */
    public function setIdusers(\Application\RelationsBundle\Entity\ChronoUsergroup $idusers)
    {
        $this->idusers = $idusers;
    
        return $this;
    }

    /**
     * Get idusers
     *
     * @return \Application\RelationsBundle\Entity\ChronoUsergroup 
     */
    public function getIdusers()
    {
        return $this->idusers;
    }

}
