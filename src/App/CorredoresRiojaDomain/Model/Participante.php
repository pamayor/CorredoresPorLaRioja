<?php

namespace App\CorredoresRiojaDomain\Model;

use App\CorredoresRiojaDomain\Model\Corredor;
use App\CorredoresRiojaDomain\Model\Carrera;
use Doctrine\ORM\Mapping as ORM;

/**
 * Participante
 *
 * @ORM\Table(name="participante", indexes={@ORM\Index(name="FK_Carrera", columns={"carreraId"}), @ORM\Index(name="FK_Corredor", columns={"corredorDni"})})
 * @ORM\Entity(repositoryClass="App\CorredoresRiojaInfrastructure\InDoctrineRepository\ParticipanteRepository")
 */
class Participante {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="tiempo", type="float", precision=10, scale=0, nullable=true)
     */
    private $tiempo;

    /**
     * @var integer
     *
     * @ORM\Column(name="dorsal", type="integer", nullable=true)
     */
    private $dorsal;

    /**
     * @var \Carrera
     *
     * @ORM\ManyToOne(targetEntity="Carrera")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carreraId", referencedColumnName="id")
     * })
     */
    private $carrera;

    /**
     * @var \Corredor
     *
     * @ORM\ManyToOne(targetEntity="Corredor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="corredorDni", referencedColumnName="dni")
     * })
     */
    private $corredor;

    function __construct(Corredor $corredor, Carrera $carrera, $id) {
        $this->corredor = $corredor;
        $this->carrera = $carrera;
        $this->id = $id;
        $this->dorsal = rand();
        $this->tiempo = 0;
    }

    function getId() {
        return $this->id;
    }

    function getCorredor() {
        return $this->corredor;
    }

    function getCarrera() {
        return $this->carrera;
    }

    function getDorsal() {
        return $this->dorsal;
    }

    function getTiempo() {
        return $this->tiempo;
    }

    function asignarMarca($tiempo) {
        $this->tiempo = $tiempo;
    }

    public function __toString() {
        return "Nombre corredor: " . $this->corredor->getNombre() . " " . $this->corredor->getApellidos() .
                " Nombre carrera: " . $this->carrera->getNombre() .
                " Dorsal: " . $this->dorsal . " Tiempo: " . $this->tiempo;
    }

}
