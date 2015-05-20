<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Пользователи и клиенты
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Company extends BaseEntity
{

    /**
     * @ORM\OneToMany(targetEntity = "Sale", mappedBy = "company")
     */
    protected $sales;

    /**
     * @ORM\OneToMany(targetEntity="Banner", mappedBy="company")
     */
    protected $banners;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank( message = "поле название обязательно для заполнения" )
     */
    protected $title;

    public function __construct(){
        $this->banners = new ArrayCollection();
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

    public function getMonthlySales(){
        $sales = array();
        foreach ($this->sales as $sale){
            $key = $sale->getDate()->format('mY');
            $key = $key*1;
            $sales[$key] = $sale;
        }
        return $sales;
    }


}