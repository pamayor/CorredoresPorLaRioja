<?php

namespace App\CorredoresRiojaBundle\Security;

use App\CorredoresRiojaBundle\Security\CorredorUser;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManager;
use App\CorredoresRiojaDomain\Model\Corredor;

class CorredorUserProvider implements UserProviderInterface {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function loadUserByUsername($username) {
        $userData = $this->entityManager->getRepository(Corredor::class)->buscarPorDNI($username);
        if ($userData) {
            $password = $userData->getPassword();
            $salt = $userData->getSalt();
            return new CorredorUser($username, $password, $salt);
        }
        throw new UsernameNotFoundException(
        sprintf('No existe un usuario con DNI "%s".', $username)
        );
    }

    public function refreshUser(UserInterface $user) {
        if (!$user instanceof CorredorUser) {
            throw new UnsupportedUserException(
            sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class) {
        return $class === 'App\CorredoresRiojaBundle\Security\CorredorUser';
    }

}
