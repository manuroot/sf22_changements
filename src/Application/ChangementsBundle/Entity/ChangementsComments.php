<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/bundles/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */

/**
 * Changements
 *
 * @ORM\Table(name="changements_comments")
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Entity\ChangementsCommentsRepository")
 */

class ChangementsComments {

   /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

   
   /**
     * @var \Application\Sonata\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\OrderBy({"username" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;
    
    
    /**
     * @var string
     * @Assert\NotBlank(message="Vous devez entrer un nom de user")
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $approved;

    /**
     * coté proprietaire
     * 
     * @ORM\ManyToOne(targetEntity="Changements", inversedBy="comments")
     * @ORM\JoinColumn(name="changement_id", referencedColumnName="id")
     */
    protected $changement;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
        $this->setApproved(true);
    }

    /**
     * @ORM\preUpdate
     */
    public function setUpdatedValue()
    {
       $this->setUpdated(new \DateTime());
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
     * Set produit
     *
     * @param \Application\ChangementsBundle\Entity\Changements $changement
     * @return ChangementsComments
     */
    public function setChangement(\Application\ChangementsBundle\Entity\Changements $changement = null)
    {
        $this->changement = $changement;
    
        return $this;
    }

    /**
     * Get produit
     *
     * @return \Application\ChangementsBundle\Entity\Changements 
     */
    public function getChangement()
    {
        return $this->changement;
    }
    

    /**
     * Set comment
     *
     * @param string $comment
     * @return EproduitComments
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     * @return EproduitComments
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    
        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return EproduitComments
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return EproduitComments
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

   

    
 /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Eproduit
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getUser() {
    //    if (!isset($this->user)){$this->user=1;}
      //  else {
            return $this->user;
            
        //    }
    }
}