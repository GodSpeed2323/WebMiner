<?php

namespace Mediashare\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Votes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mediashare\ProductBundle\Entity\VotesRepository")
 */
class Votes
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
     * @var integer
     *
     * @ORM\Column(name="iduser", type="integer")
     */
    private $iduser;

    /**
     * @var integer
     *
     * @ORM\Column(name="idpublication", type="integer")
     */
    private $idpublication;

    /**
     * @var integer
     *
     * @ORM\Column(name="idconcours", type="integer")
     */
    private $idconcours;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;


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
     * Set iduser
     *
     * @param integer $iduser
     * @return Votes
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;

        return $this;
    }

    /**
     * Get iduser
     *
     * @return integer
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * Set idpublication
     *
     * @param integer $idpublication
     * @return Votes
     */
    public function setIdpublication($idpublication)
    {
        $this->idpublication = $idpublication;

        return $this;
    }

    /**
     * Get idpublication
     *
     * @return integer
     */
    public function getIdpublication()
    {
        return $this->idpublication;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Votes
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set idconcours
     *
     * @param integer $idconcours
     * @return Votes
     */
    public function setIdconcours($idconcours)
    {
        $this->idconcours = $idconcours;

        return $this;
    }

    /**
     * Get idconcours
     *
     * @return integer 
     */
    public function getIdconcours()
    {
        return $this->idconcours;
    }
}
