<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Список баннеров пакета
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class PackageBanner extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="Package", inversedBy="banners")
     */
    protected $package;

    /**
     * @ORM\Column(type="string", length=15 ,nullable=true)
     */
    protected $gid;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $area;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $adrs;

    /**
     * Долгота
     * @ORM\Column(type="string", nullable=true)
     */
    protected $longitude;

    /**
     * Широта
     * @ORM\Column(type="string", nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank( message = "поле Текст обязательно для заполнения" )
     */
    protected $body;

    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    protected $grp;

    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    protected $ots;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $side;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $format;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $img;

    /**
     * @return mixed
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param mixed $package
     */
    public function setPackage($package)
    {
        $this->package = $package;
    }

    /**
     * @return mixed
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * @param mixed $gid
     */
    public function setGid($gid)
    {
        $this->gid = $gid;
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
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getAdrs()
    {
        return $this->adrs;
    }

    /**
     * @param mixed $adrs
     */
    public function setAdrs($adrs)
    {
        $this->adrs = $adrs;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getGrp()
    {
        return $this->grp;
    }

    /**
     * @param mixed $grp
     */
    public function setGrp($grp)
    {
        $this->grp = $grp;
    }

    /**
     * @return mixed
     */
    public function getOts()
    {
        return $this->ots;
    }

    /**
     * @param mixed $ots
     */
    public function setOts($ots)
    {
        $this->ots = $ots;
    }

    /**
     * @return mixed
     */
    public function getSide()
    {
        return $this->side;
    }

    /**
     * @param mixed $side
     */
    public function setSide($side)
    {
        $this->side = $side;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param mixed $img
     */
    public function setImg($img)
    {
        $this->img = $img;
    }



}