<?php

namespace App\CorredoresRiojaBundle\Form\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegistroOrganizadorCommand {

    private $id;

    /**
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @Assert\NotBlank()
     */
    private $descripcion;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\NotBlank()
     */
    private $password;

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
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

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPassword($password) {
        $this->password = $password;
    }

}
