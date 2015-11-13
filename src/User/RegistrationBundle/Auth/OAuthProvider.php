<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace User\RegistrationBundle\Auth;

use Symfony\Component\Security\Core\User\UserInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use User\RegistrationBundle\Entity\Users;
use User\RegistrationBundle\Services\CreateDir;

class OAuthProvider extends OAuthUserProvider
{
    /**
     * @var array
     */
    protected $properties;
    
    private $doctrine, $createDir;

    public function __construct($doctrine, CreateDir $createDir, array $properties)
    {
        $this->properties = $properties;
        $this->createDir = $createDir;
        $this->doctrine=$doctrine;
    }

    public function loadUserByUsername($username)
    {
        if(empty($username)){
            return;
        }
        // получаем данные о пользователе
        /** @var $user \User\RegistrationBundle\Entity\Users */
        $user=$this->getUserByUsername($username);

        if($user&&$user->getId()){
            return $user;
        }

        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.',$username));
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $name=$response->getRealName();// имя пользователя на стороне oAuth-сервера, например:Nikolay Lebedenko
        $username=$response->getUsername();// уникальный ID пользователя на стороне oAuth-сервера, например:8d86a051742940e3
        $token = $response->getAccessToken();// токен (уникальный идентификатор) для авторизации, например:ZxC1/2+3 (более 255 символов)
        $path = $response->getProfilePicture();// изображение профиля, может не быть, например:пусто

        if(empty($username)){
            throw new UsernameNotFoundException('Вы не идентифицированы т.к. не получен Email-адрес');
        }
        
        $user=$this->getUserByUsername($username);// находим пользователя
        /** @var $user \User\RegistrationBundle\Entity\Users */

        // если пользователя нет в базе данных - добавим его
        if(!$user||!$user->getId()){
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            
            $user=new Users();
            $user->setRealname($name);
            $user->setUsername($username);
            $user->$setter_id($username);
            $user->$setter_token($token);
            $user->setPassword(sha1($username));
            
            $roles = $this->doctrine->getRepository('UserRegistrationBundle:Roles')
                    ->findOneBy(array('role' => 'ROLE_USER'));
            
            $user->addRole($roles);
            
            $this->doctrine->getManager()->persist($user);
            $this->doctrine->getManager()->flush();
            
            $dir = __DIR__.'/../../../../web/public/xml/Users/'.$user->getId();
            $this->createDir->createDir($dir);
            $this->createDir->createDir($dir.'/avatar');

            $user_id=$user->getId();
        }else{
            $user_id=$user->getId();
        }

        if(!$user_id){
            throw new UsernameNotFoundException('Возникла проблема добавления или определения пользователя');
        }

        return $this->loadUserByUsername($username);
    }
    
    public function refreshUser(UserInterface $user)
    {
        if(!$user instanceof User){
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.',get_class($user)));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'User\\RegistrationBundle\\Entity\\Users';
    }
    
    private function getUserByUsername($username='')
    {
        return $user = $this->doctrine->getRepository('UserRegistrationBundle:Users')->findOneBy(array('username'=>$username));
    }
    
    /**
     * Gets the property for the response.
     *
     * @param UserResponseInterface $response
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getProperty(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();

        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        return $this->properties[$resourceOwnerName];
    }
}
?>