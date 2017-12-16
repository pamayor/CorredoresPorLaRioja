<?php

namespace App\CorredoresRiojaDomain\Repository;
use App\CorredoresRiojaDomain\Model\Carrera;
use App\CorredoresRiojaDomain\Model\Corredor;

interface IParticipanteRepository {

    public function inscribirCorredorEnCarrera(Corredor $corredor, Carrera $carrera);

    public function buscarTodos();

    public function buscarParticipantesDeCarrera($id);

    public function buscarCarrerasDisputadasONoDeCorredor($dni, $disputed);

    public function comprobarCorredorEnCarrera($dni, $id);

    public function actualizarTiempoCorredorEnCarrera($dni, $id, $time);

    public function eliminar($id);
}
