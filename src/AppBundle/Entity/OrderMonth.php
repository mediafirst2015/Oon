<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Заказы месяц
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class OrderMonth extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="months")
     */
    protected $order;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank( message = "поле дата обязательно для заполнения" )
     */
    protected $date;

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
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

}