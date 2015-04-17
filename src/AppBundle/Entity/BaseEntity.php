<?php
namespace AppBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\MappedSuperclass
 */
abstract class BaseEntity
{
    /** @ORM\Column(type = "boolean") */
    protected $enabled = true;

    /**
     * @ORM\Column(type = "datetime")
     * @Gedmo\Timestampable(on = "create")
     */
    protected $created;

    /**
     * @ORM\Column(type = "datetime")
     * @Gedmo\Timestampable(on = "update")
     */
    protected $updated;

    /**
     * @ORM\Id
     * @ORM\Column(type = "integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Publication
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Publication
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Publication
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    public function getEnabledStr(){
        if ($this->enabled == true){
            return 'Активна';
        }else{
            return 'Неактивна';
        }
    }
}