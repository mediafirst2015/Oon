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
class Sale extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="sales")
     */
    protected $company;

    /**
     * @ORM\ManyToOne(targetEntity="Banner", inversedBy="sales")
     */
    protected $banner;

    /**
     * @ORM\ManyToOne(targetEntity = "City", inversedBy = "sales")
     */
    protected $city;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank( message = "поле дата обязательно для заполнения" )
     */
    protected $date;

    /**
     * @ORM\Column(type="integer")
     */
    protected $percent;

    public function __toString(){
        return $this->percent;
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
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param mixed $percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }


}
