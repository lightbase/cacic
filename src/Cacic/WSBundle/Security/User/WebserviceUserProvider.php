<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 23/09/14
 * Time: 23:43
 */

namespace Cacic\WSBundle\Security\User;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Cacic\CommonBundle\Entity\Usuario as WebserviceUser;


class WebserviceUserProvider implements UserProviderInterface {

    private $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();
    }


    public function getUsernameForApiKey($apiKey)
    {
        // Look up the username based on the token in the database, via
        // an API call, or do something entirely different
        $usuario = $this->em->getRepository('CacicCommonBundle:Usuario')->findOneBy(array('apiKey' => $apiKey));

        if ($usuario) {
            return $usuario->getUsername();
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $apiKey)
        );
    }

    public function loadUserByUsername($username)
    {
        // make a call to your webservice here
        //$userData = '...';
        // pretend it returns an array on success, false if there is no user
        $usuario = $this->em->getRepository('CacicCommonBundle:Usuario')->findOneBy(array('nmUsuarioAcesso' => $username));

        if ($usuario) {
            return $usuario;
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Cacic\CommonBundle\Entity\Usuario';
    }

} 