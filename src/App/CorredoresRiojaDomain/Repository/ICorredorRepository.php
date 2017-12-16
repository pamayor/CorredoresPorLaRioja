<?php

namespace App\CorredoresRiojaDomain\Repository;
use App\CorredoresRiojaDomain\Model\Corredor;

interface ICorredorRepository {

    public function buscarPorDNI($dni);

    public function buscarTodos();

    public function guardar(Corredor $corredor);

    public function eliminar(Corredor $corredor);

    public function actualizar(Corredor $corredor);
}
