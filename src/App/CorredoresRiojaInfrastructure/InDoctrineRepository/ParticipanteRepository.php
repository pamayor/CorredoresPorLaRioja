<?php

namespace App\CorredoresRiojaInfrastructure\InDoctrineRepository;

use App\CorredoresRiojaDomain\Repository\IParticipanteRepository;
use App\CorredoresRiojaDomain\Model\Carrera;
use App\CorredoresRiojaDomain\Model\Corredor;
use App\CorredoresRiojaDomain\Model\Participante;
use Doctrine\ORM\EntityRepository;

class ParticipanteRepository extends EntityRepository implements IParticipanteRepository {

    public function actualizarTiempoCorredorEnCarrera($dni, $id, $time) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $participante = this::obtenerCorredorEnCarrera($dni, $id);
            if (count($participante)==0) {
                $participante->asignarMarca($time);
                $entityManager->merge($participante);
                $entityManager->flush();
                $entityManager->getConnection()->commit();
            }
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

    public function buscarCarrerasDisputadasONoDeCorredor($dni, $disputed) {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Participante::class);
        if ($disputed) {
            $query = $repository->createQueryBuilder('participante')
                    ->innerJoin('participante.carrera', 'carrera')
                    ->innerJoin('participante.corredor', 'corredor')
                    ->where('carrera.fechaCelebracion < :date')
                    ->andWhere('corredor.dni = :dni')
                    ->setParameter('dni', $dni)
                    ->setParameter('date', (new \DateTime("now"))->format("Y-m-d"))
                    ->getQuery();
        } else {
            $query = $repository->createQueryBuilder('participante')
                    ->innerJoin('participante.carrera', 'carrera')
                    ->innerJoin('participante.corredor', 'corredor')
                    ->where('carrera.fechaCelebracion >= :date')
                    ->andWhere('corredor.dni = :dni')
                    ->setParameter('dni', $dni)
                    ->setParameter('date', (new \DateTime("now"))->format("Y-m-d"))
                    ->getQuery();
        }
        return $query->getResult();
    }

    public function buscarParticipantesDeCarrera($id) {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Participante::class);
        $query = $repository->createQueryBuilder('participante')
                ->innerJoin('participante.carrera', 'carrera')
                ->where('carrera.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        return $query->getResult();
    }

    public function buscarTodos() {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(Participante::class)->findAll();
    }

    public function comprobarCorredorEnCarrera($dni, $id) {
        if (this::obtenerCorredorEnCarrera($dni, $id)) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminar($id) {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $participante = $entityManager->find('App\CorredoresRiojaDomain\Model\Participante', $id);
            $entityManager->remove($participante);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

    public function inscribirCorredorEnCarrera(Corredor $corredor, Carrera $carrera) {
        $participante = new Participante($corredor, $carrera, null);
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->persist($participante);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
        }
    }

    public function obtenerCorredorEnCarrera($dni, $id) {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Participante::class);
        $query = $repository->createQueryBuilder('participante')
                ->innerJoin('participante.carrera', 'carrera')
                ->innerJoin('participante.corredor', 'corredor')
                ->where('carrera.id = :id')
                ->andWhere('corredor.dni = :dni')
                ->setParameter('id', $id)
                ->setParameter('dni', $dni)
                ->getQuery();
        return $query->getResult();
    }

}
