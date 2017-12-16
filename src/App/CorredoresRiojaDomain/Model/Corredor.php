<?php

namespace App\CorredoresRiojaDomain\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Corredor
 *
 * @ORM\Table(name="corredor")
 * @ORM\Entity(repositoryClass="App\CorredoresRiojaInfrastructure\InDoctrineRepository\CorredorRepository")
 */
class Corredor {

    /**
     * @var string
     *
     * @ORM\Column(name="dni", type="string", length=10, nullable=false)
     * @ORM\Id
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=30, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=60, nullable=true)
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=30, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=100, nullable=true)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=200, nullable=true)
     */
    private $direccion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaNacimiento", type="datetime", nullable=true)
     */
    private $fechaNacimiento;

    function __construct($dni, $nombre, $apellidos, $email, $password, $direccion, $fechaNacimiento) {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->password = $password;
        $this->salt = "";
        $this->direccion = $direccion;
        $this->fechaNacimiento = $fechaNacimiento;
    }

    function getDni() {
        return $this->dni;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellidos() {
        return $this->apellidos;
    }

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function getSalt() {
        return $this->salt;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    public function __toString() {
        return "DNI: " . $this->dni .
                " Nombre y apellidos: " . $this->nombre . " " . $this->apellidos .
                " Email: " . $this->email .
                " Dirección: " . $this->direccion .
                "Fecha de nacimiento: " . $this->fechaNacimiento;
    }

    /**
     * @Assert\IsTrue( message = "La contraseña no puede ser la misma que tu nombre")
     */
    public function isPasswordLegal() {
        return $this->nombre != $this->password;
    }

    /**
     * @Assert\True( message = "Tienes que ser mayor de edad para registrarte")
     */
    public function isMayorEdad() {
        $currentyear = getdate()['year'];
        $birthdayyear = ( $this->fechaNacimiento->format('Y'));
        $diff_years = ( $currentyear - $birthdayyear );
        return $diff_years >= 18;
    }

    public function saveEncodedPassword($password) {
        $this->password = $password;
    }

}
