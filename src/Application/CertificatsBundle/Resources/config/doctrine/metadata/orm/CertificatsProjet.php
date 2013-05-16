<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table(name="certificats_projet")
 * @ORM\Entity
 */
class Projet
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
     * @ORM\Column(name="nomprojet", type="string", length=40, nullable=false)
     */
    private $nomprojet;


}
