<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Страницы
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Page extends BaseEntity
{

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле Заголовок обязательно для заполнения" )
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank( message = "поле Текст обязательно для заполнения" )
     */
    protected $body;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле URL обязательно для заполнения" )
     */
    protected $url;

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
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }


}
