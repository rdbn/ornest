<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace User\RegistrationBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="User\RegistrationBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 */

Class Users implements UserInterface, EquatableInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="vkontakte_id", type="string", nullable=true, options={"default":""})
     */
    protected $vkontakte_id;
    
    /** 
     * @ORM\Column(name="vkontakte_access_token", type="string", length=255, nullable=true, options={"default":""}) 
     */
    protected $vkontakte_access_token;
    
    /** 
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true, options={"default":""}) 
     */
    protected $facebook_id;
 
    /** 
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true, options={"default":""}) 
     */
    protected $facebook_access_token;
 
    /** 
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true, options={"default":""}) 
     */
    protected $google_id;
 
    /** 
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true, options={"default":""}) 
     */
    protected $google_access_token;
    
    /**
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    protected $username;
    
    /**
     * @ORM\Column(name="realname", type="string", length=255)
     */
    protected $realname;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $salt;

    /**
     * @ORM\Column(name="email", type="string", length=60, nullable=true)
     */
    protected $email;
    
    /**
     * @ORM\Column(name="street", type="string", length=45, nullable=true, options={"default":""})
     */
    protected $street;
    
    /**
     * @ORM\Column(name="home_index", type="integer", nullable=true)
     */
    protected $home_index;
    
    /**
     * @ORM\Column(name="phone", type="bigint", nullable=true)
     */
    protected $phone;
    
    /**
     * @ORM\Column(name="skype", type="string", length=45, nullable=true, options={"default":""})
     */
    protected $skype;
    
    /**
     * @Assert\Image(
     *     maxSize = "1M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *     maxSizeMessage = "Максимальный вес картинки 1MB.",
     *     mimeTypesMessage = "Только таких типов ихображений можно загрузитью"
     * )
     */
    protected $file;
    
    /**
     * @ORM\Column(name="path", type="string", length=255, nullable=true, options={"default":""})
     */
    protected $path;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive;

    /**
     * @ORM\Column(name="description", type="string", length=255, nullable=true, options={"default":""})
     */
    protected $description;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="work_hour_start", type="time", nullable=true)
     */
    protected $workHourStart;
    /**
     * @var integer
     *
     * @ORM\Column(name="work_hour_end", type="time", nullable=true)
     */
    protected $workHourEnd;
    
    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    protected $city;
    
    /**
     * @ORM\ManyToMany(targetEntity="Roles", inversedBy="users")
     * @ORM\JoinTable(name="users_roles")
     */
    protected $roles;
    
    /**
     * @ORM\ManyToMany(targetEntity="Shop\CreateBundle\Entity\Shops", mappedBy="like_shop")
     */
    protected $shop;
    
    /**
     * @ORM\ManyToMany(targetEntity="Shop\ProductBundle\Entity\Product", mappedBy="like_product")
     *
     */
    protected $product;
    
    /**
     * @ORM\ManyToMany(targetEntity="Shop\OrderBundle\Entity\Order", mappedBy="users")
     *
     */
    protected $order;
    
    /**
     * @ORM\ManyToMany(targetEntity="Shop\CreateBundle\Entity\Shops", mappedBy="users")
     *
     */
    protected $shopUsers;

    /**
     * @ORM\OneToMany(targetEntity="Shop\CreateBundle\Entity\Shops", mappedBy="manager")
     */
    protected $shopManager;
    
    /**
     * @ORM\OneToMany(targetEntity="User\FriendsBundle\Entity\Friends", mappedBy="users")
     */
    protected $friends;

    /**
     * Construct with Class users
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->shopManager = new ArrayCollection();
        $this->shopUsers = new ArrayCollection();
        $this->product = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->shop = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        $dir = __DIR__.'/../../../../web/'.$this->getUploadDir();

        if (!file_exists($dir)) {
            mkdir($dir, 0775);
        }

        return $dir;
    }

    protected function getUploadDir()
    {
        return '/public/images/avatar';
    }
    
    public function preUpload()
    {   
        if (null !== $this->file) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->file->guessExtension();
            
            return $this->getUploadDir().'/'.$this->path;
        }
    }
    
    public function upload()
    {
        if (null === $this->file) return;

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->path
        );

        $this->file = null;
    }
    
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }
    
    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }
    
    /**
     * @return string
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }
    public function getRolesAsCollection()
    {
        return $this->roles;
    }
    public function setRoles($role)
    {
        return $this->roles->add($role);
    }
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            ) = unserialize($serialized);
    }

    public function isEqualTo(UserInterface $user)
    {
        if ($this->getId() === $user->getId()) {
            return true;
        }
        return false;
    }
    
    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Users
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

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
     * Set vkontakte_id
     *
     * @param string $vkontakteId
     * @return Users
     */
    public function setVkontakteId($vkontakteId)
    {
        $this->vkontakte_id = $vkontakteId;
    
        return $this;
    }

    /**
     * Get vkontakte_id
     *
     * @return string 
     */
    public function getVkontakteId()
    {
        return $this->vkontakte_id;
    }

    /**
     * Set vkontakte_access_token
     *
     * @param string $vkontakteAccessToken
     * @return Users
     */
    public function setVkontakteAccessToken($vkontakteAccessToken)
    {
        $this->vkontakte_access_token = $vkontakteAccessToken;
    
        return $this;
    }

    /**
     * Get vkontakte_access_token
     *
     * @return string 
     */
    public function getVkontakteAccessToken()
    {
        return $this->vkontakte_access_token;
    }

    /**
     * Set facebook_id
     *
     * @param string $facebookId
     * @return Users
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;
    
        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return Users
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;
    
        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set google_id
     *
     * @param string $googleId
     * @return Users
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;
    
        return $this;
    }

    /**
     * Get google_id
     *
     * @return string 
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set google_access_token
     *
     * @param string $googleAccessToken
     * @return Users
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->google_access_token = $googleAccessToken;
    
        return $this;
    }

    /**
     * Get google_access_token
     *
     * @return string 
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }

    /**
     * Set realname
     *
     * @param string $realname
     * @return Users
     */
    public function setRealname($realname)
    {
        $this->realname = $realname;
    
        return $this;
    }

    /**
     * Get realname
     *
     * @return string 
     */
    public function getRealname()
    {
        return $this->realname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Users
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set home_index
     *
     * @param integer $homeIndex
     * @return Users
     */
    public function setHomeIndex($homeIndex)
    {
        $this->home_index = $homeIndex;
    
        return $this;
    }

    /**
     * Get home_index
     *
     * @return integer 
     */
    public function getHomeIndex()
    {
        return $this->home_index;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     * @return Users
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return integer 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set skype
     *
     * @param string $skype
     * @return Users
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    
        return $this;
    }

    /**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Users
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set workHourStart
     *
     * @param \DateTime $workHourStart
     * @return Users
     */
    public function setWorkHourStart($workHourStart)
    {
        $this->workHourStart = $workHourStart;
    
        return $this;
    }

    /**
     * Get workHourStart
     *
     * @return \DateTime 
     */
    public function getWorkHourStart()
    {
        return $this->workHourStart;
    }

    /**
     * Set workHourEnd
     *
     * @param \DateTime $workHourEnd
     * @return Users
     */
    public function setWorkHourEnd($workHourEnd)
    {
        $this->workHourEnd = $workHourEnd;
    
        return $this;
    }

    /**
     * Get workHourEnd
     *
     * @return \DateTime 
     */
    public function getWorkHourEnd()
    {
        return $this->workHourEnd;
    }

    /**
     * Set city
     *
     * @param \User\RegistrationBundle\Entity\City $city
     * @return Users
     */
    public function setCity(\User\RegistrationBundle\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return \User\RegistrationBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add roles
     *
     * @param \User\RegistrationBundle\Entity\Roles $roles
     * @return Users
     */
    public function addRole(\User\RegistrationBundle\Entity\Roles $roles)
    {
        $this->roles[] = $roles;
    
        return $this;
    }

    /**
     * Remove roles
     *
     * @param \User\RegistrationBundle\Entity\Roles $roles
     */
    public function removeRole(\User\RegistrationBundle\Entity\Roles $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Add shop
     *
     * @param \Shop\CreateBundle\Entity\Shops $shop
     * @return Users
     */
    public function addShop(\Shop\CreateBundle\Entity\Shops $shop)
    {
        $this->shop[] = $shop;
    
        return $this;
    }

    /**
     * Remove shop
     *
     * @param \Shop\CreateBundle\Entity\Shops $shop
     */
    public function removeShop(\Shop\CreateBundle\Entity\Shops $shop)
    {
        $this->shop->removeElement($shop);
    }

    /**
     * Get shop
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Add shopManager
     *
     * @param \Shop\CreateBundle\Entity\Shops $shopManager
     * @return Users
     */
    public function addShopManager(\Shop\CreateBundle\Entity\Shops $shopManager)
    {
        $this->shopManager[] = $shopManager;
    
        return $this;
    }

    /**
     * Remove shopManager
     *
     * @param \Shop\CreateBundle\Entity\Shops $shopManager
     */
    public function removeShopManager(\Shop\CreateBundle\Entity\Shops $shopManager)
    {
        $this->shopManager->removeElement($shopManager);
    }

    /**
     * Get shopManager
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShopManager()
    {
        return $this->shopManager;
    }

    /**
     * Add shopUsers
     *
     * @param \Shop\CreateBundle\Entity\Shops $shopUsers
     * @return Users
     */
    public function addShopUser(\Shop\CreateBundle\Entity\Shops $shopUsers)
    {
        $this->shopUsers[] = $shopUsers;
    
        return $this;
    }

    /**
     * Remove shopUsers
     *
     * @param \Shop\CreateBundle\Entity\Shops $shopUsers
     */
    public function removeShopUser(\Shop\CreateBundle\Entity\Shops $shopUsers)
    {
        $this->shopUsers->removeElement($shopUsers);
    }

    /**
     * Get shopUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShopUsers()
    {
        return $this->shopUsers;
    }

    /**
     * Add friends
     *
     * @param \User\FriendsBundle\Entity\Friends $friends
     * @return Users
     */
    public function addFriend(\User\FriendsBundle\Entity\Friends $friends)
    {
        $this->friends[] = $friends;
    
        return $this;
    }

    /**
     * Remove friends
     *
     * @param \User\FriendsBundle\Entity\Friends $friends
     */
    public function removeFriend(\User\FriendsBundle\Entity\Friends $friends)
    {
        $this->friends->removeElement($friends);
    }

    /**
     * Get friends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * Add product
     *
     * @param \Shop\ProductBundle\Entity\Product $product
     * @return Users
     */
    public function addProduct(\Shop\ProductBundle\Entity\Product $product)
    {
        $this->product[] = $product;
    
        return $this;
    }

    /**
     * Remove product
     *
     * @param \Shop\ProductBundle\Entity\Product $product
     */
    public function removeProduct(\Shop\ProductBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add order
     *
     * @param \Shop\OrderBundle\Entity\Order $order
     * @return Users
     */
    public function addOrder(\Shop\OrderBundle\Entity\Order $order)
    {
        $this->order[] = $order;
    
        return $this;
    }

    /**
     * Remove order
     *
     * @param \Shop\OrderBundle\Entity\Order $order
     */
    public function removeOrder(\Shop\OrderBundle\Entity\Order $order)
    {
        $this->order->removeElement($order);
    }

    /**
     * Get order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Users
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
