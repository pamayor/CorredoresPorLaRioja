<?php

namespace App\CorredoresRiojaInfrastructure\InMemoryRepository;

use App\CorredoresRiojaDomain\Repository\IOrganizacionRepository;
use App\CorredoresRiojaDomain\Model\Organizacion;

class OrganizacionRepository implements IOrganizacionRepository {

    private $organizaciones;

    public function __construct() {
        $this->organizaciones = array();
    }

    public function actualizar(Organizacion $organizacion) {
        foreach ($this->organizaciones as $key => $value) {
            if ($value->getId() == $organizacion->getId()) {
                $this->organizaciones = array_replace($this->organizaciones, array($key => $organizacion));
            }
        }
    }

    public function buscarPorEmail($email) {
        foreach ($this->organizaciones as $value) {
            if ($value->getEmail() == $email) {
                return $value;
            }
        }
    }

    public function buscarPorSlug($slug) {
        foreach ($this->organizaciones as $value) {
            if ($value->getSlug() == $slug) {
                return $value;
            }
        }
    }

    public function buscarTodos() {
        return $this->organizaciones;
    }

    public function eliminar(Organizacion $organizacion) {
        foreach ($this->organizaciones as $key => $value) {
            if ($value->getId() == $organizacion->getId()) {
                unset($this->organizaciones[$key]);
            }
        }
    }

    public function guardar(Organizacion $organizacion) {
        $this->organizaciones[] = $organizacion;
    }

}
