<?php

namespace Application\CertificatsBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Filetype
 *
 * @ORM\Table(name="certificats_filetype")
 * @ORM\Entity(repositoryClass="Application\CertificatsBundle\Repository\CertificatsFileTypeRepository")
 */
class CertificatsFiletype
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
     * @ORM\Column(name="file_type", type="string", length=20, nullable=false)
     */
    private $fileType;

    /**
     * @var string
     *
     * @ORM\Column(name="infos", type="string", length=50, nullable=false)
     */
    private $infos;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", nullable=false)
     */
    private $details;

    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string", length=20, nullable=false)
     */
    private $folder;



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
     * @param string $fileType
     * @return Filetype
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    
        return $this;
    }

    /**
     * Get fileType
     *
     * @return string 
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * Set infos
     *
     * @param string $infos
     * @return Filetype
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
     * Set details
     *
     * @param string $details
     * @return Filetype
     */
    public function setDetails($details)
    {
        $this->details = $details;
    
        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set folder
     *
     * @param string $folder
     * @return Filetype
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;
    
        return $this;
    }

    /**
     * Get folder
     *
     * @return string 
     */
    public function getFolder()
    {
        return $this->folder;
    }
 public function __toString() {
        return $this->getFileType();    // this will not look good if SonataAdminBundle uses this ;)
    }
    
}