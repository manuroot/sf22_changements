<?php

namespace Application\ChangementsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;


/**
 * Changements
 *
 * @ORM\Table(name="mychangements")
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Entity\MychangementsRepository")
 * @ORM\HasLifecycleCallbacks
 */



class Mychangements
{
/**
 * @var integer
 *
 * @ORM\Column(name="id", type="integer")
 * @ORM\Id
 * @ORM\GeneratedValue(strategy="AUTO")
 */
private $id;

/**
 * @var string
 *
 * @ORM\Column(name="name", type="string", length=255)
 */
private $name;

/**
 * @var string
 *
 * @ORM\Column(name="description", type="text")
 */
private $description;

/**
 * @var float
 *
 * @ORM\Column(name="price", type="float")
 */
private $price;

/**
 * @var string
 *
 * @ORM\Column(name="type", type="string", length=255)
 */
private $type;

/**
 * @var integer
 *
 * @ORM\Column(name="owner", type="integer")
 */
private $owner;

/**
 * @var boolean
 *
 * @ORM\Column(name="available", type="boolean")
 */
private $available;

/**
 *
 *
 * @ORM\OneToMany(targetEntity="Image", mappedBy="property", cascade={"persist"})
 */
private $images;

public function __construct()
{
    $this->images = new ArrayCollection();
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
 * Set name
 *
 * @param string $name
 * @return Property
 */
public function setName($name)
{
    $this->name = $name;

    return $this;
}

/**
 * Get name
 *
 * @return string 
 */
public function getName()
{
    return $this->name;
}

/**
 * Set description
 *
 * @param string $description
 * @return Property
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
 * Set price
 *
 * @param float $price
 * @return Property
 */
public function setPrice($price)
{
    $this->price = $price;

    return $this;
}

/**
 * Get price
 *
 * @return float 
 */
public function getPrice()
{
    return $this->price;
}

/**
 * Set type
 *
 * @param string $type
 * @return Property
 */
public function setType($type)
{
    $this->type = $type;

    return $this;
}

/**
 * Get type
 *
 * @return string 
 */
public function getType()
{
    return $this->type;
}

/**
 * Set owner
 *
 * @param integer $owner
 * @return Property
 */
public function setOwner($owner)
{
    $this->owner = $owner;

    return $this;
}

/**
 * Get owner
 *
 * @return integer 
 */
public function getOwner()
{
    return $this->owner;
}

/**
 * Set available
 *
 * @param boolean $available
 * @return Property
 */
public function setAvailable($available)
{
    $this->available = $available;

    return $this;
}

/**
 * Get available
 *
 * @return boolean 
 */
public function getAvailable()
{
    return $this->available;
}

/**
 * Add images
 *
 * @param \Mata\MainBundle\Entity\Image $images
 * @return Property
 */
public function addImage(\Mata\MainBundle\Entity\Image $images)
{
    $this->images[] = $images;

    return $this;
}

public function setImages(ArrayCollection $images)
{
    foreach ($images as $image) {
        $image->setProperty($this);
    }

    $this->images = $images;
}

/**
 * Remove images
 *
 * @param \Mata\MainBundle\Entity\Image $images
 */
public function removeImage(\Mata\MainBundle\Entity\Image $images)
{
    $this->images->removeElement($images);
}

/**
 * Get images
 *
 * @return \Doctrine\Common\Collections\Collection 
 */
public function getImages()
{
    return $this->images;
}
}