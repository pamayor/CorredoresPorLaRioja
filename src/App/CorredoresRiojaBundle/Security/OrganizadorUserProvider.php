<?php

namespace App\CorredoresRiojaBundle\Security;

use App\CorredoresRiojaBundle\Security\OrganizadorUser;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManager;
use App\CorredoresRiojaDomain\Model\Organizacion;

class OrganizadorUserProvider implements UserProviderInterface {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function loadUserByUsername($username) {
        $userData = $this->entityManager->getRepository(Organizacion::class)->buscarPorEmail($username);
        if ($userData) {
            $password = $userData->getPassword();
            $salt = $userData->getSalt();
            return new OrganizadorUser($username, $password, $salt);
        }
        throw new UsernameNotFoundException(
        sprintf('No existe un usuario organizacion con DNI "%s".', $username)
        );
    }

    public function refreshUser(UserInterface $user) {
        if (!$user instanceof OrganizadorUser) {
            throw new UnsupportedUserException(
            sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class) {
        return $class === 'App\CorredoresRiojaBundle\Security\OrganizadorUser';
    }

}
