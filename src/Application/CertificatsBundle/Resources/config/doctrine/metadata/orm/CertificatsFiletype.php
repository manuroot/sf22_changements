<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CertificatsFiletype
 *
 * @ORM\Table(name="certificats_filetype")
 * @ORM\Entity
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


}
