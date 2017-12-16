<?php

namespace App\CorredoresRiojaDomain\Repository;
use App\CorredoresRiojaDomain\Model\Organizacion;

interface IOrganizacionRepository {

    public function buscarPorSlug($slug);

    public function buscarPorEmail($email);

    public function buscarTodos();

    public function guardar(Organizacion $organizacion);

    public function eliminar(Organizacion $organizacion);

    public function actualizar(Organizacion $organizacion);
}
