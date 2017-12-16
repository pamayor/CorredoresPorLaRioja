<?php

namespace App\CorredoresRiojaInfrastructure\InDoctrineRepository;

use App\CorredoresRiojaDomain\Repository\ICarreraRepository;
use App\CorredoresRiojaDomain\Model\Carrera;
use Doctrine\ORM\EntityRepository;

class CarreraRepository extends EntityRepository implements ICarreraRepository {

    public function actualizar(Carrera $carrera) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->merge($carrera);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

    public function buscarDisputadasONo($disputed) {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Carrera::class);
        if ($disputed) {
            $query = $repository->createQueryBuilder('carrera')
                    ->where('carrera.fechaCelebracion < :date')
                    ->setParameter('date', (new \DateTime("now"))->format("Y-m-d"))
                    ->getQuery();
        } else {
            $query = $repository->createQueryBuilder('carrera')
                    ->where('carrera.fechaCelebracion >= :date')
                    ->setParameter('date', (new \DateTime("now"))->format("Y-m-d"))
                    ->getQuery();
        }
        return $query->getResult();
    }

    public function buscarPorOrganizacionDisputadasONo($id, $disputed) {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Carrera::class);
        if ($disputed) {
            $query = $repository->createQueryBuilder('carrera')
                    ->innerJoin('carrera.organizacion', 'organizacion')
                    ->where('carrera.fechaCelebracion < :fechaCelebracion')
                    ->andWhere('organizacion.id = :organizacion')
                    ->setParameter('fechaCelebracion', (new \DateTime("now"))->format("Y-m-d"))
                    ->setParameter('organizacion', $id)
                    ->getQuery();
        } else {
            $query = $repository->createQueryBuilder('carrera')
                    ->innerJoin('carrera.organizacion', 'organizacion')
                    ->where('carrera.fechaCelebracion >= :fechaCelebracion')
                    ->andWhere('organizacion.id = :organizacion')
                    ->setParameter('fechaCelebracion', (new \DateTime("now"))->format("Y-m-d"))
                    ->setParameter('organizacion', $id)
                    ->getQuery();
        }
        return $query->getResult();
    }

    public function buscarPorSlug($slug) {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(Carrera::class)->findOneBySlug($slug);
    }

    public function buscarTodos() {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(Carrera::class)->findAll();
    }

    public function eliminar(Carrera $carrera) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->remove($carrera);
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

    public function guardar(Carrera $carrera) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->persist($carrera);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

}
