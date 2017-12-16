<?php

namespace App\CorredoresRiojaDomain\Repository;
use App\CorredoresRiojaDomain\Model\Carrera;

interface ICarreraRepository {

    public function buscarPorSlug($slug);

    public function buscarTodos();

    public function guardar(Carrera $carrera);

    public function eliminar(Carrera $carrera);

    public function actualizar(Carrera $carrera);

    public function buscarPorOrganizacionDisputadasONo($id, $disputed);

    public function buscarDisputadasONo($disputed);
}
