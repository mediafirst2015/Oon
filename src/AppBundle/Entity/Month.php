<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Стоимость и статусы по месяцам
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Month extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="Banner", inversedBy="months")
     */
    protected $banner;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank( message = "поле дата обязательно для заполнения" )
     */
    protected $date;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $price;

    /**
     * @ORM\Column(type="integer")
     */
    protected $sale;

    /**
     * @ORM\ManyToOne(targetEntity = "City", inversedBy = "sales")
     */
    protected $city;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status = 1;


    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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
    public function setStatus($status = true)
    {
        $this->status = $status;
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
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * @param mixed $sale
     */
    public function setSale($sale)
    {
        $this->sale = $sale;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }



}
