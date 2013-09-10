<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Application\ChangementsBundle\Entity\Changements;



//use Application\CentralBundle\Model\DocchangementsBase;

/**
 * Projet

 * @ORM\Table(name="changements_fichiers")
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Repository\DocchangementsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Docchangements {
//class Docchangements  extends DocchangementsBase {
// * @Orm\MappedSuperclass

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    // champs supplemanetaire de saisie
    protected $name;

    /**
     * champs supplemanetaire md5
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $md5;

    /**
     * @Assert\File(maxSize="5M",
     *    notFoundMessage = "Le fichier n'a pas été trouvé sur le disque",
     *    uploadErrorMessage = "Erreur dans l'upload du fichier"
     * )
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    // champs pour nom local aletatoire
    protected $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    // nom origine du fichier
    protected $OriginalFilename;

    /**
     * Date/Time of the update
     *
     * @var \Datetime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;
    protected $temp;

    /**
     *
     * @var ArrayCollection Projet $idchangements
     *
     * Inverse Side
     * 
     * @ORM\ManyToMany(targetEntity="Changements", mappedBy="picture",cascade={"persist"})
     */
    protected $idchangement;

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
        //   parent::__construct();
        $this->idchangement = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * Add idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     * @return Docchangements
     */
    public function addIdchangement(Changements $idchangement) {
        if (!$this->idchangement->contains($idchangement)) {
            if (!$idchangement->getPicture()->contains($this)) {

                $idchangement->addPicture($this);  // Lie le Client au produit.
            }
            $this->idchangement->add($idchangement);
        }
        //$this->idchangement[] = $idchangement;
        //  return $this;
    }

    public function setIdchangement($items) {
        if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addIdchangement($item);
            }
        } elseif ($items instanceof Changements) {
            $this->addPicture($items);
        } else {
            throw new \Exception("$items must be an instance of Changements or ArrayCollection");
        }
    }

    /**
     * Remove idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     */
    public function removeIdchangement(Changements $idchangement) {
        if (!$this->idchangement->contains($idchangement)) {
            return;
        }
        $this->idchangement->removeElement($idchangement);
        $idchangement->removePicture($this);
    }

    /**
     * Get idchangement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdchangement() {
        return $this->idchangement;
    }

    /**
     * Get id
     *
     * @return UploadedFile
     * fichier temporaire: ex: /tmp/php702KS7
     */
    // private $OriginalFilename;

    public function getFile() {
        return $this->file;
    }

    /**
     * Set file
     *
     * 
     * @param UploadedFile $file
     * @return Document
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }

        return $this;
    }

    /**
     * Set disk path
     * 
     */
    public function setDiskPath($disk_path = 'uploads/documents') {
        $this->disk_path = $disk_path;
    }

    /**
     * Get disk path
     * 
     */
    public function getDiskPath() {
        return $this->disk_path;
    }

    public function __toString() {
        return $this->getName();    // this will not look good if SonataAdminBundle uses this ;)
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    public function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return $this->disk_path;
    }

    /**
     * Avant le persist et l'update (fichier deja uploadé)
     * 
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        $this->updatedAt = new \DateTime();
        // si upload de fichier (temp file)
        if (null !== $this->file) {

            // faites ce que vous voulez pour générer un nom unique
            // a remettre apres
            //  $this->path=$this->generateNewFilename();

            $ext = null;
            $this->OriginalFilename = $this->getFile()->getClientOriginalName();
            $fic = $this->OriginalFilename;
            $info = pathinfo($fic);
            if (isset($info)) {
                $ext = $info['extension'];
            }
            if (!isset($ext)) {
                $ext = $this->file->guessExtension();
            }
            if (!isset($ext)) {
                $ext = "bin";
            }
            $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $ext;


            // recup nom origne

            if (!$this->name || $this->name == "")
                $this->name = $this->OriginalFilename;
            $this->md5 = md5_file($this->file);
            $this->updatedAt = new \DateTime();
            // echo "here";exit(1);
        }
        // check du md5
        if (!$this->md5 && (file_exists($this->getUploadDir() . '/' . $this->path))) {
            $this->md5 = md5_file($this->getUploadDir() . '/' . $this->path);
        }
        // check du nom
        if (!$this->name || $this->name == "TOTO") {
            $this->name = $this->OriginalFilename;
        }
        //    echo "here";exit(1);
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        // unset($this->file);
        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * Generates a non-random-filename
     *
     * @return string A non-random name to represent the current file
     */
    public function getFilename() {
        // nom du fichier
        $filename = $this->getFile()->getClientOriginalName();
        $ext = $this->getFile()->guessExtension();
        //  $ext = $this->getFile()->getExtension();
        // if ($ext !==null)


        echo "root dir=" . $this->getUploadRootDir() . "<br>";
        echo "file=$filename<br>";
        $name = substr($filename, 0, - strlen($ext));
        $i = 1;
        //  $this->getUploadRootDir(), $this->path;
        $fullpath = $this->getUploadRootDir();
        while (file_exists($fullpath . '/' . $filename)) {
            $filename = $name . '-' . $i . $ext;
            $i++;
        }
        echo "ext=$ext<br>";
        echo "new name=$filename<br>";
        exit(1);


        return array($filename, $ext,);
    }

    public function generateNewFilename() {
        // nom du fichier
        $filename = $this->getFile()->getClientOriginalName();
        $ext = $this->getFile()->guessExtension();
        //  $ext = $this->getFile()->getExtension();
        // if ($ext !==null)


        echo "root dir=" . $this->getUploadRootDir() . "<br>";
        echo "file=$filename<br>";
        if (isset($ext) && strlen($ext) > 0) {
            $name = substr($filename, 0, - (strlen($ext) + 1));
            $i = 1;
            //  $this->getUploadRootDir(), $this->path;
            $fullpath = $this->getUploadRootDir();
            while (file_exists($fullpath . '/' . $filename)) {
                $filename = $name . '-' . $i . '.' . $ext;
                $i++;
            }
        }
        else
            $name = $filename;

        echo "ext=$ext<br>name=$name<br>";
        echo "new name=$filename<br>";
        exit(1);


        return ($filename);
    }

    public function generatePathFileName($file) {
        return $file->getOriginalName();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            if (file_exists($file))
                unlink($file);
        }
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad() {
        //  nimporte koi!!
        //   $this->updatedAt = new \DateTime();
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
     * Set name
     *
     * @param string $name
     * @return Document
     */
    public function setName($name) {
        // $this->name = $name;
        if (isset($name) && $name != "")
            $this->name = $name;
        else
            $this->name = "TOTO";
        return $this;
    }

    /**
     *  Set name
     *
     * @param string $name
     * @return Document
     */
    public function setOriginalFilename() {
        // $this->name = $name;

        $this->OriginalFilename = $this->getFile()->getClientOriginalName();

        //$this->file;}

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getOriginalFilename() {
        return $this->OriginalFilename;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Docchangements
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Set CreatedAt
     *
     * @param \DateTime $CreatedAt
     * @return Docchangements
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get CreatedAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set md5
     *
     * @param string $md5
     * @return Docchangements
     */
    public function setMd5($md5) {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get md5
     *
     * @return string 
     */
    public function getMd5() {
        return $this->md5;
    }

}