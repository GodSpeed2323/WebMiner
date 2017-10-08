<?php

namespace Mediashare\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mediashare\AppBundle\Entity\ServerRepository")
 */
class Server
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
     * @ORM\Column(name="points_seconde", type="string", length=255)
     */
    private $pointsSeconde;

    /**
     * @var string
     *
     * @ORM\Column(name="points_total", type="string", length=255)
     */
    private $pointsTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="nb_online", type="integer", nullable=true)
     */
    private $nbOnline;

    /**
     * @var string
     *
     * @ORM\Column(name="xmr_total", type="string", length=255)
     */
    private $xmrTotal;


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
     * Set pointsSeconde
     *
     * @param string $pointsSeconde
     * @return Server
     */
    public function setPointsSeconde($pointsSeconde)
    {
        $this->pointsSeconde = $pointsSeconde;

        return $this;
    }

    /**
     * Get pointsSeconde
     *
     * @return string 
     */
    public function getPointsSeconde()
    {
        return $this->pointsSeconde;
    }

    /**
     * Set pointsTotal
     *
     * @param string $pointsTotal
     * @return Server
     */
    public function setPointsTotal($pointsTotal)
    {
        $this->pointsTotal = $pointsTotal;

        return $this;
    }

    /**
     * Get pointsTotal
     *
     * @return string 
     */
    public function getPointsTotal()
    {
        return $this->pointsTotal;
    }

    /**
     * Set nbOnline
     *
     * @param string $nbOnline
     * @return integer
     */
    public function setNbOnline($nbOnline)
    {
        $this->nbOnline = $nbOnline;

        return $this;
    }

    /**
     * Get nbOnline
     *
     * @return integer 
     */
    public function getNbOnline()
    {
        return $this->nbOnline;
    }

    /**
     * Set xmrTotal
     *
     * @param string $xmrTotal
     * @return Server
     */
    public function setXmrTotal($xmrTotal)
    {
        $this->xmrTotal = $xmrTotal;

        return $this;
    }

    /**
     * Get xmrTotal
     *
     * @return string 
     */
    public function getXmrTotal()
    {
        return $this->xmrTotal;
    }
}
