<?php

namespace App\CorredoresRiojaInfrastructure\InDoctrineRepository;

use App\CorredoresRiojaDomain\Repository\IOrganizacionRepository;
use App\CorredoresRiojaDomain\Model\Organizacion;
use Doctrine\ORM\EntityRepository;

class OrganizacionRepository extends EntityRepository implements IOrganizacionRepository {

    public function actualizar(Organizacion $organizacion) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->merge($organizacion);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

    public function buscarPorEmail($email) {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(Organizacion::class)->findOneByEmail($email);
    }

    public function buscarPorSlug($slug) {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(Organizacion::class)->findOneBySlug($slug);
    }

    public function buscarTodos() {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(Organizacion::class)->findAll();
    }

    public function eliminar(Organizacion $organizacion) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->remove($organizacion);
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

    public function guardar(Organizacion $organizacion) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->persist($organizacion);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

}
