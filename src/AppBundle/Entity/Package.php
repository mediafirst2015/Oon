<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Список пакетов
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Package extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="packages")
     */
    protected $author;

    /**
     * @ORM\OneToMany(targetEntity="PackageBanner", mappedBy="package")
     */
    protected $banners;

    /**
     * 1 - Свободно
     * 2 - нужно запрашивать
     * 3 - Занято
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $status = 0;

    /**
     * @ORM\Column(type="float", scale=2)
     */
    protected $price = 0;

    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    protected $ots;

    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    protected $grp;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank( message = "поле дата начала обязательно для заполнения" )
     */
    protected $dateStart;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank( message = "поле дата окончания обязательно для заполнения" )
     */
    protected $dateEnd;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $img;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $hot = false;

    /**
     *
     */
    public function __construct(){
        $this->banners = new ArrayCollection();
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
     * @param $banner
     */
    public function addBanner($banner){
        $this->banners[] = $banner;
    }

    /**
     * @param $banner
     */
    public function removeBanner($banner){
        $this->banners->removeElement($banner);
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
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param mixed $dateStart
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return mixed
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param mixed $dateEnd
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
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