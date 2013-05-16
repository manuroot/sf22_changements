<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/bundles/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */

class User extends BaseUser
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer $id
     */
    
     /**
     * @var \ChronoUserGroup
     *
     * @ORM\ManyToOne(targetEntity="Application\RelationsBundle\Entity\EserviceGroup",inversedBy="users", cascade={"persist", "merge"}))
     * @ORM\OrderBy({"nomGroup" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_group", referencedColumnName="id",nullable=false)
     * })
     * @ORM\OrderBy({"nom_group" = "ASC"})
    */
    private $idgroup;
    
    
      
     
      
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set idgroup
     *
     * @param \Application\EservicesBundle\Entity\ChronoUsergroup $idgroup
     * @return ChronoUser
     */
    public function setIdgroup(\Application\RelationsBundle\Entity\Eservicegroup $idgroup=null)
    {
        $this->idgroup = $idgroup;
    
        return $this;
    }

    /**
     * Get idgroup
     *
     * @return Application\RelationsBundle\Entity\Eservicegroup
     */
    public function getIdgroup()
    {
        return $this->idgroup;
    }
   
    /**
     * Constructor
     */
    public function __construct()
    {
     
    }
    
    
}