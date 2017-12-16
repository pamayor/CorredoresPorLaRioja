<?php

namespace App\CorredoresRiojaInfrastructure\InMemoryRepository;

use App\CorredoresRiojaDomain\Repository\IParticipanteRepository;
use App\CorredoresRiojaDomain\Model\Carrera;
use App\CorredoresRiojaDomain\Model\Corredor;
use App\CorredoresRiojaDomain\Model\Participante;

class ParticipanteRepository implements IParticipanteRepository {

    private $participantes;

    public function __construct() {
        $this->participantes = array();
    }

    public function actualizarTiempoCorredorEnCarrera($dni, $id, $time) {
        foreach ($this->participantes as $key => $value) {
            if ($value->getCorredor()->getDni() == $dni && $value->getCarrera()->getId() == $id) {
                $value->asignarMarca($time);
                $this->participantes = array_replace($this->participantes, array($key => $value));
            }
        }
    }

    public function buscarCarrerasDisputadasONoDeCorredor($dni, $disputed){
        $carreras = array();
        foreach ($this->participantes as $value) {
            if ($value->getCorredor()->getDni() == $dni && $disputed && $value->getCarrera()->getFechaCelebracion()->format("Y-m-d") < (new \DateTime("now"))->format("Y-m-d")) {
                array_push($carreras, $value);
            } else if ($value->getCorredor()->getDni() == $dni && !$disputed && $value->getCarrera()->getFechaCelebracion()->format("Y-m-d") >= (new \DateTime("now"))->format("Y-m-d")) {
                array_push($carreras, $value);
            }
        }
        return $carreras;
    }

    public function buscarParticipantesDeCarrera($id) {
        $corredores = array();
        foreach ($this->participantes as $value) {
            if ($value->getCarrera()->getId() == $id) {
                array_push($corredores, $value);
            }
        }
        return $corredores;
    }

    public function buscarTodos() {
        return $this->participantes;
    }

    public function comprobarCorredorEnCarrera($dni, $id) {
        foreach ($this->participantes as $value) {
            if ($value->getCorredor()->getDni() == $dni && $value->getCarrera()->getId() == $id) {
                return true;
            }
        }
        return false;
    }

    public function eliminar($id) {
        foreach ($this->participantes as $key => $value) {
            if ($value->getId() == $id) {
                unset($this->participantes[$key]);
            }
        }
    }

    public function inscribirCorredorEnCarrera(Corredor $corredor, Carrera $carrera) {
        $participante = new Participante($corredor, $carrera);
        $this->participantes[] = $participante;
    }

}
