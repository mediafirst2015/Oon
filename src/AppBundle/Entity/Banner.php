<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Список баннеров
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass = "BannerRepository")
 */
class Banner extends BaseEntity
{

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="banner")
     */
    protected $orders;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="banners")
     */
    protected $author;

    /**
     * @ORM\OneToMany(targetEntity="Month", mappedBy="banner")
     */
    protected $months;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $area;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле Заголовок обязательно для заполнения" )
     */
    protected $title;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $hot = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $comment;

    /**
     * @ORM\Column(type="integer")
     */
    protected $light = 0;

    /**
     * @ORM\Column(type="string")
     */
    protected $type = 'BB';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $img;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $adrs;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $price = 0;

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
     * @ORM\Column(type="integer", nullable=true)
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


    public function __construct(){
        $this->orders = new ArrayCollection();
        $this->months = new ArrayCollection();
    }

    public function getId(){
        return $this->id;
    }

    public function __toString(){
        return $this->title;
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
    public function getBody()
    {
        return $this->body;
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
    public function getTitle()
    {
        return $this->title;
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
    public function getAdrs()
    {
        return $this->adrs;
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
    public function getLatitude()
    {
        return $this->latitude;
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
    public function getLongitude()
    {
        return $this->longitude;
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
    public function setPrice($price = 0)
    {
        $this->price = $price;
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

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    public function addOrder($order){
        $this->orders[] = $order;
    }

    public function removeOrder($order){
        $this->orders->removeElement($order);
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
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
    public function getLight()
    {
        return $this->light;
    }

    /**
     * @param mixed $light
     */
    public function setLight($light = 0)
    {
        $this->light = $light;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type = 'BB')
    {
        $this->type = $type;
    }

    public function isMonth($date){
        $months = $this->months;
        foreach ($months as $m){
            if ( $m->getDate() == $date ){
                return $m->getStatus();
            }
        }
        return 1;
    }

    /**
     * @return mixed
     */
    public function getHot()
    {
        return $this->hot;
    }

    /**
     * @param mixed $hot
     */
    public function setHot($hot)
    {
        $this->hot = $hot;
    }


}
