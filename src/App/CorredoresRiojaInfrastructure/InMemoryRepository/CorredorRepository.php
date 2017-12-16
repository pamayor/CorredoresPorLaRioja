<?php

namespace App\CorredoresRiojaInfrastructure\InMemoryRepository;

use App\CorredoresRiojaDomain\Repository\ICorredorRepository;
use App\CorredoresRiojaDomain\Model\Corredor;

class CorredorRepository implements ICorredorRepository {

    private $corredores;

    public function __construct() {
        $this->corredores = array();
    }

    public function actualizar(Corredor $corredor) {
        foreach ($this->corredores as $key => $value) {
            if ($value->getDni() == $corredor->getDni()) {
                $this->corredores = array_replace($this->corredores, array($key => $corredor));
            }
        }
    }

    public function buscarPorDNI($dni) {
        foreach ($this->corredores as $value) {
            if ($value->getDni() == $dni) {
                return $value;
            }
        }
        return null;
    }

    public function buscarTodos() {
        return $this->corredores;
    }

    public function eliminar(Corredor $corredor) {
        foreach ($this->corredores as $key => $value) {
            if ($value->getDni() == $corredor->getDni()) {
                unset($this->corredores[$key]);
            }
        }
    }

    public function guardar(Corredor $corredor) {
        $this->corredores[] = $corredor;
    }

}
