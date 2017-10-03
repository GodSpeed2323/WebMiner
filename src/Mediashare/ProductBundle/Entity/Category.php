<?php

namespace Mediashare\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Mediashare\AdminBundle\Entity\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Mediashare\ProductBundle\Entity\CategoryRepository")
 */
class Category
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
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $childrens;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="childrens")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner ce champs")
     * @Assert\Length(
     *      max = "255",
     *      maxMessage = "Ce champs ne peut pas être plus long que {{ limit }} caractères"
     * )
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @var integer
     *
     * @ORM\Column(name="nb_win", type="integer")
     */
    private $nb_win;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_photo", type="integer")
     */
    private $nb_photo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="promotion_start_date", type="date", nullable=true)
     */
    private $promotionStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="promotion_end_date", type="date", nullable=true)
     */
    private $promotionEndDate;


    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     * @ORM\OrderBy({"position" = "ASC"})
     **/
    private $products;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

     /**
     *
     * @ORM\ManyToMany(targetEntity="Mediashare\AdminBundle\Entity\File", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="category_file",
     *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    public $pictures;

     /**
     * @var boolean
     *
     * @ORM\Column(name="online", type="boolean")
     */
    private $online;


    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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
     * Set parent
     *
     * @param string $parent
     * @return Category
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function addChildren(Category $children)
    {
        $this->childrens[] = $children;

        $children->setParent($this);

        return $this;
    }

    public function removeChildren(Category $children)
    {
        $this->childrens->removeElement($children);
        $children->setParent(null);
    }

    public function getChildrens()
    {
        return $this->childrens;
    }


    public function setTitle($title)
    {
        $this->title = $title;

        $this->setSlug($this->title);

        return $this;
    }
    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Product
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Add Pictures
     *
     * @param File $picture
     * @return Category
     */
    public function addPicture(File $picture)
    {
        $this->pictures[] = $picture;

        return $this;
    }

    public function removePicture(File $picture)
    {
        $this->pictures->removeElement($picture);
    }

    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set online
     *
     * @param boolean $online
     * @return Product
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return boolean
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Set idCat
     *
     * @param integer $idCat
     * @return Category
     */
    public function setIdCat($idCat)
    {
        $this->idCat = $idCat;

        return $this;
    }

    /**
     * Get idCat
     *
     * @return integer
     */
    public function getIdCat()
    {
        return $this->idCat;
    }

    /**
     * Set nb_photo
     *
     * @param integer $nb_photo
     * @return Category
     */
    public function setNbPhoto($nb_photo)
    {
        $this->nb_photo = $nb_photo;

        return $this;
    }

    /**
     * Get nb_photo
     *
     * @return integer
     */
    public function getNbPhoto()
    {
        return $this->nb_photo;
    }
    public function getnb_photo()
    {
        return $this->nb_photo;
    }

    /**
     * Set nb_win
     *
     * @param integer $nb_win
     * @return Category
     */
    public function setNbWin($nb_win)
    {
        $this->nb_win = $nb_win;

        return $this;
    }

    /**
     * Get nb_win
     *
     * @return integer
     */
   public function getNbWin()
   {
       return $this->nb_win;
   }
   public function getnb_win()
   {
       return $this->nb_win;
   }

   /**
    * Get promotionStartDate
    *
    * @return \DateTime
    */
   public function getPromotionStartDate()
   {
       return $this->promotionStartDate;
   }

   /**
    * Set promotionStartDate
    *
    * @param \DateTime $promotionEndDate
    * @return Product
    */
   public function setPromotionStartDate($promotionStartDate)
   {
       $this->promotionStartDate = $promotionStartDate;

       return $this;
   }
   /**
    * Set promotionEndDate
    *
    * @param \DateTime $promotionEndDate
    * @return Product
    */
   public function setPromotionEndDate($promotionEndDate)
   {
       $this->promotionEndDate = $promotionEndDate;

       return $this;
   }

   /**
    * Get promotionEndDate
    *
    * @return \DateTime
    */
   public function getPromotionEndDate()
   {
       return $this->promotionEndDate;
   }

    public function addProduct(Product $product)
    {
        $this->products->add($product);

        return $this;
    }

    public function removeProduct(Product $product)
    {
        $this->products->remove($product);

        return $this;
    }

    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug =  $this->slugify($slug);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }




    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    public function getSlugParent()
    {
        if(!$this->parent)
        {
            return "root";
        }
        return $this->parent->getSlug();
    }
}
