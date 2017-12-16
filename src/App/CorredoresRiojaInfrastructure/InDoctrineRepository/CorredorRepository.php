<?php

namespace App\CorredoresRiojaInfrastructure\InDoctrineRepository;

use App\CorredoresRiojaDomain\Repository\ICorredorRepository;
use App\CorredoresRiojaDomain\Model\Corredor;
use Doctrine\ORM\EntityRepository;

class CorredorRepository extends EntityRepository implements ICorredorRepository {

    public function actualizar(Corredor $corredor) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->merge($corredor);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

    public function buscarPorDNI($dni) {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(Corredor::class)->findOneByDni($dni);
    }

    public function buscarTodos() {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(Corredor::class)->findAll();
    }

    public function eliminar(Corredor $corredor) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->remove($corredor);
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

    public function guardar(Corredor $corredor) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->persist($corredor);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

}
