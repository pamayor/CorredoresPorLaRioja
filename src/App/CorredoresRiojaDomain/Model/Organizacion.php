<?php

namespace App\CorredoresRiojaDomain\Model;

use App\Utils\Utils;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Organizacion
 *
 * @ORM\Table(name="organizacion")
 * @ORM\Entity(repositoryClass="App\CorredoresRiojaInfrastructure\InDoctrineRepository\OrganizacionRepository")
 */
class Organizacion {

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
     * @ORM\Column(name="slug", type="string", length=50, nullable=true)
     */
    private $slug;

    function __construct($id, $nombre, $descripcion, $email, $password) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->email = $email;
        $this->password = $password;
        $this->salt = "";
        $this->slug = Utils::getSlug($nombre);
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

    function getEmail() {
        return $this->email;
    }

    function getPassword() {
        return $this->password;
    }

    function getSalt() {
        return $this->salt;
    }

    function getSlug() {
        return $this->slug;
    }

    public function __toString() {
        return "Id: " . $this->id .
                " Nombre: " . $this->nombre .
                " Descripción: " . $this->descripcion .
                " Email: " . $this->email;
    }

    /**
     * @Assert\IsTrue( message = "La contraseña no puede ser la misma que tu nombre")
     */
    public function isPasswordLegal() {
        return $this->nombre != $this->password;
    }

    public function saveEncodedPassword($password) {
        $this->password = $password;
    }

}
