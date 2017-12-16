<?php

namespace App\CorredoresRiojaInfrastructure\InMemoryRepository;

use App\CorredoresRiojaDomain\Repository\ICarreraRepository;
use App\CorredoresRiojaDomain\Model\Carrera;

class CarreraRepository implements ICarreraRepository {

    private $carreras;

    public function __construct() {
        $this->carreras = array();
    }

    public function actualizar(Carrera $carrera) {
        foreach ($this->carreras as $key => $value) {
            if ($value->getId() == $carrera->getId()) {
                $this->carreras = array_replace($this->carreras, array($key => $carrera));
            }
        }
    }

    public function buscarDisputadasONo($disputed) {
        $carretasFiltradas = array();
        foreach ($this->carreras as $value) {
            if ($disputed && $value->getFechaCelebracion()->format("Y-m-d") < (new \DateTime("now"))->format("Y-m-d")) {
                array_push($carretasFiltradas, $value);
            } else if (!$disputed && $value->getFechaCelebracion()->format("Y-m-d") >= (new \DateTime("now"))->format("Y-m-d")) {
                array_push($carretasFiltradas, $value);
            }
        }
        return $carretasFiltradas;
    }

    public function buscarPorOrganizacionDisputadasONo($id, $disputed) {
        $carretasFiltradas = array();
        foreach ($this->carreras as $value) {
            if ($disputed && $value->getFechaCelebracion()->format("Y-m-d") < (new \DateTime("now"))->format("Y-m-d") && $value->getOrganizacion()->getId() == $id) {
                array_push($carretasFiltradas, $value);
            } else if (!$disputed && $value->getFechaCelebracion()->format("Y-m-d") >= (new \DateTime("now"))->format("Y-m-d") && $value->getOrganizacion()->getId() == $id) {
                array_push($carretasFiltradas, $value);
            }
        }
        return $carretasFiltradas;
    }

    public function buscarPorSlug($slug) {
        foreach ($this->carreras as $value) {
            if ($value->getSlug() == $slug) {
                return $value;
            }
        }
    }

    public function buscarTodos() {
        return $this->carreras;
    }

    public function eliminar(Carrera $carrera) {
        foreach ($this->carreras as $key => $value) {
            if ($value->getId() == $carrera->getId()) {
                unset($this->carreras[$key]);
            }
        }
    }

    public function guardar(Carrera $carrera) {
        $this->carreras[] = $carrera;
    }

}
