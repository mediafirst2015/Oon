<?php
namespace AppBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name = "city")
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\Column(type = "integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type = "string")
     * @Assert\NotBlank(message = "Пожалуйста, укажите название города.")
     * @Assert\Length(max = 63, maxMessage = "Название города не может быть длиннее {{limit}}.")
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity = "Banner", mappedBy = "city")
     */
    protected $banners;

    /**
     * @ORM\OneToMany(targetEntity = "Sale", mappedBy = "city")
     */
    protected $sales;

    /**
     * @ORM\Column(type = "boolean", nullable = true)
     */
    protected $enabled = true;


    public function __construct()
    {
        $this->banners = new ArrayCollection();
        $this->sales = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBanners()
    {
        return $this->banners;
    }

    /**
     * @param mixed $banners
     */
    public function setBanners($banners)
    {
        $this->banners = $banners;
    }

    public function addBanner($banner){
        $this->banners[] = $banner;
    }

    public function removeBanner($banner){
        $this->banners->removeElement($banner);
    }

    /**
     * @return mixed
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * @param mixed $sales
     */
    public function setSales($sales)
    {
        $this->sales = $sales;
    }

    public function addSale($sale){
        $this->sales[] = $sale;
    }

    public function removeSale($sale){
        $this->sales->removeElement($sale);
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled = 1)
    {
        $this->enabled = $enabled;
    }


}