<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;

/**
 * Отзывы
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @FileStore\Uploadable
 */
class Review extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле Заголовок обязательно для заполнения" )
     */
    protected $title;

    /**
     * @Assert\File( maxSize="3M")
     * @FileStore\UploadableField(mapping="review")
     * @ORM\Column(type="array", nullable=true)
     **/
    protected $image;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank( message = "поле отзыв обязательно для заполнения" )
     */
    protected $body;

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
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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



}