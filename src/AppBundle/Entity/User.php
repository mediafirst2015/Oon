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
class User extends BaseEntity implements UserInterface
{

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="client")
     */
    protected $orders;

    /**
     * @ORM\OneToMany(targetEntity="Banner", mappedBy="author")
     */
    protected $banners;

    /**
     * @ORM\OneToMany(targetEntity="Package", mappedBy="author")
     */
    protected $packages;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank( message = "поле E-mail обязательно для заполнения" )
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank( message = "поле Фамилия обязательно для заполнения" )
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank( message = "поле Имя обязательно для заполнения" )
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $surName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank( message = "поле пароль обязательно для заполнения" )
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $roles;

    /**
     * @ORM\Column(type="string", length=250)
     */
    protected $company;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $phone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $hot;


    public function __construct(){
        $this->roles    = 'ROLE_UNCONFIRMED';
        $this->orders = new ArrayCollection();
        $this->banners = new ArrayCollection();
        $this->packages = new ArrayCollection();
    }

    public function __toString(){
        return $this->lastName . ' ' . mb_substr($this->firstName, 0, 1, 'utf-8') . '.' . ($this->surName ? ' ' . mb_substr($this->surName, 0, 1, 'utf-8') . '.' : '');
    }

    static public function getRolesNames(){
        return array(
            "ROLE_USER"     => "Пользователь",
            "ROLE_OPERATOR" => "Администратор",
            "ROLE_ADMIN"    => "Оператор",
        );
    }

    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }


    /**
     * @return mixed
     */
    public function getRoles()
    {
        return explode(';', $this->roles);
    }



    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        if (is_array($roles)) {
            $roles = implode($roles, ';');
        }

        $this->roles = $roles;
    }

    public function removeRole($role)
    {
        $roles = explode(';', $this->roles);
        $key   = array_search($role, $roles);

        if ($key !== false) {
            unset($roles[$key]);
            $this->roles = implode($roles, ';');
        }
    }

    public function checkRole($role)
    {
        $roles = explode(';', $this->roles);

        return in_array($role, $roles);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Сброс прав пользователя.
     */
    public function eraseCredentials()
    {
        return true;
    }

    public function isEqualTo(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    /**
     * Сериализуем только id, потому что UserProvider сам перезагружает остальные свойства пользователя по его id
     *
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id
            ) = unserialize($serialized);
    }
    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param mixed $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getSurName()
    {
        return $this->surName;
    }

    /**
     * @param mixed $surName
     */
    public function setSurName($surName)
    {
        $this->surName = $surName;
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

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
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

    public function addBanner($banner){
        $this->banners[] = $banner;
    }

    public function removeBanner($banner){
        $this->banners->removeElement($banner);
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

    /**
     * @return mixed
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @param mixed $packages
     */
    public function setPackages($packages)
    {
        $this->packages = $packages;
    }

    public function addPackage($package){
        $this->packages[] = $package;
    }

    public function removePackage($package){
        $this->packages->removeElement($package);
    }



}