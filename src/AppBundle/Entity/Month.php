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
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank( message = "поле стоимость обязательно для заполнения" )
     */
    protected $price;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status = 1;

    /**
     * @return mixed
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param mixed $map
     */
    public function setMap($map)
    {
        $this->map = $map;
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



}
