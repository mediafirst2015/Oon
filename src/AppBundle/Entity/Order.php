<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Заказы
 *
 * @ORM\Table(name="BannerOrder")
 * @ORM\Entity(repositoryClass = "OrderRepository")
 */
class Order extends BaseEntity
{
    /**
     * В качестве поля группировки используется например дата
     */
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле группировка обязательна для сохранения" )
     */
    protected $orderGroup;

    /**
     * @ORM\ManyToOne(targetEntity="Banner", inversedBy="orders")
     */
    protected $banner;

    /**
     * @ORM\OneToMany(targetEntity="OrderMonth", mappedBy="order")
     */
    protected $months;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     */
    protected $client;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank( message = "поле стоимость обязательно для заполнения" )
     */
    protected $price;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status = 1;

    public function __construct(){
        $this->months = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @param mixed $banner
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * @param mixed $months
     */
    public function setMonths($months)
    {
        $this->months = $months;
    }

    public function addMonth($month){
        $this->months[] = $month;
    }

    public function removeMonth($month){
        $this->months->removeElement($month);
    }

    /**
     * @return mixed
     */
    public function getOrderGroup()
    {
        return $this->orderGroup;
    }

    /**
     * @param mixed $orderGroup
     */
    public function setOrderGroup($orderGroup)
    {
        $this->orderGroup = $orderGroup;
    }

    public function isMonth($date){
        $months = $this->months;
        foreach ($months as $m){
            if ( $m->getDate() == $date ){
                return true;
            }
        }
        return false;
    }
}