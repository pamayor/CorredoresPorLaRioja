<?php

namespace App\CorredoresRiojaDomain\Model;

use App\CorredoresRiojaDomain\Model\Organizacion;
use App\Utils\Utils;
use Doctrine\ORM\Mapping as ORM;

/**
 * Carrera
 *
 * @ORM\Table(name="carrera")
 * @ORM\Entity(repositoryClass="App\CorredoresRiojaInfrastructure\InDoctrineRepository\CarreraRepository")
 */
class Carrera {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=200, nullable=true)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCelebracion", type="datetime", nullable=true)
     */
    private $fechaCelebracion;

    /**
     * @var integer
     *
     * @ORM\Column(name="distancia", type="integer", nullable=true)
     */
    private $distancia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaLimiteInscripcion", type="datetime", nullable=true)
     */
    private $fechaLimiteInscripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="numeroMaximoParticipantes", type="integer", nullable=true)
     */
    private $numeroMaximoParticipantes;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=200, nullable=true)
     */
    private $imagen;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=50, nullable=true)
     */
    private $slug;

    /**
     * @var \Organizacion
     *
     * @ORM\ManyToOne(targetEntity="Organizacion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organizacion_id", referencedColumnName="id")
     * })
     */
    private $organizacion;

    function __construct($id, $nombre, $descripcion, $fechaCelebracion, $distancia, $fechaLimiteInscripcion, $numeroMaximoParticipantes, $imagen, Organizacion $organizacion) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->fechaCelebracion = $fechaCelebracion;
        $this->distancia = $distancia;
        $this->fechaLimiteInscripcion = $fechaLimiteInscripcion;
        $this->numeroMaximoParticipantes = $numeroMaximoParticipantes;
        $this->imagen = $imagen;
        $this->slug = Utils::getSlug($nombre);
        $this->organizacion = $organizacion;
    }

    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getFechaCelebracion() {
        return $this->fechaCelebracion;
    }

    function getDistancia() {
        return $this->distancia;
    }

    function getFechaLimiteInscripcion() {
        return $this->fechaLimiteInscripcion;
    }

    function getNumeroMaximoParticipantes() {
        return $this->numeroMaximoParticipantes;
    }

    function getImagen() {
        return $this->imagen;
    }

    function getSlug() {
        return $this->slug;
    }

    function getOrganizacion() {
        return $this->organizacion;
    }

    public function __toString() {
        return "Id: " . $this->id .
                " Nombre: " . $this->nombre .
                " Descripción: " . $this->descripcion .
                " Fecha de celebracion: " . $this->fechaCelebracion .
                " Distancia: " . $this->distancia .
                " Fecha límite de inscripcion: " . $this->fechaLimiteInscripcion .
                " Número máximo de participantes: " . $this->numeroMaximoParticipantes;
    }

}
