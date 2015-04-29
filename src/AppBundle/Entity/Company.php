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





}