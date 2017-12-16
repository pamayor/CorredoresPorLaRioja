<?php

namespace App\CorredoresRiojaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\CorredoresRiojaDomain\Model\Organizacion;
use App\CorredoresRiojaDomain\Model\Carrera;

class CarreraRepositoryTest extends KernelTestCase {

    private $repository;
    private $container;

    protected function setUp() {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
        $this->repository = $this->container->get('carrerarepository');
    }

    public function testCarrerasEsCarrerasDisputadasYNoDisputadas() {
        $carreras = $this->repository->buscarTodos();
        $carrerasNoDisputadas = $this->repository->buscarDisputadasONo(FALSE);
        $carrerasDisputadas = $this->repository->buscarDisputadasONo(TRUE);
        foreach ($carrerasDisputadas as $carrera) {
            $this->assertContains($carrera, $carreras);
        }
        foreach ($carrerasNoDisputadas as $carrera) {
            $this->assertContains($carrera, $carreras);
        }
    }

    public function testAnadirCarrera() {
        $org1 = new Organizacion(1, "Ayuntamiento Matute", "El ayuntamiento de matute", "matute@gmail.com", "matute");
        $carrera = new Carrera(1, "Carrera Montes Anguiano", "Primera carrera por los montes de Anguiano", new \DateTime("2015-06-15"), 10, new \DateTime("2015-06-14"), 50, "anguiano.jpg", $org1);
        $this->repository->guardar($carrera);
        $this->assertNotNull($this->repository->buscarPorSlug('carrera-montes-anguiano'));
    }

    public function testCarrerasPorDisputar() {
        $carrerasNoDisputadas = $this->repository->buscarDisputadasONo(FALSE);
        foreach ($carrerasNoDisputadas as $carrera) {
            $this->assertTrue($carrera->getFechacelebracion()->format("Y-m-d") >= (new \DateTime('now'))->format("Y-m-d"));
        }
    }

    public function testEliminarCarrera() {
        $carrera = $this->repository->buscarPorSlug('carrera-montes-anguiano');
        $this->repository->eliminar($carrera);
        $this->assertNull($this->repository->buscarPorSlug('carrera-montes-anguiano'));
    }

    public function testCarrerasDisputadas() {
        $carrerasDisputadas = $this->repository->buscarDisputadasONo(TRUE);
        foreach ($carrerasDisputadas as $carrera) {
            $this->assertTrue($carrera->getFechacelebracion()->format("Y-m-d") < (new \DateTime('now'))->format("Y-m-d"));
        }
    }
}
