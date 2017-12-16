<?php

namespace App\CorredoresRiojaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Validator\Validation;
use App\CorredoresRiojaDomain\Model\Corredor;

class CorredorTest extends TestCase {

    private $validator;

    protected function setUp() {
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    // Validamos el nombre
    public function testValidarNombre() {
        $corredor = new Corredor(null, "pepe", "perez", "peperez@gmail.com", "pepe", "Calle falsa", new \DateTime('1980-08-08'));
        $listaErrores = $this->validator->validate($corredor);
        $this->assertGreaterThan(0, $listaErrores->count(), 'El nombre no puede coincidir con la contraseña');
        $error = $listaErrores[0];
        $this->assertEquals('La contraseña no puede ser la misma que tu nombre', $error->getMessage());
    }
    
    // Validamos la edad
    public function testValidarEdad() {
        $corredor = new Corredor(null, "pepe", "perez", "peperez@gmail.com", "1234", "Calle falsa", new \DateTime('2010-08-08'));
        $listaErrores = $this->validator->validate($corredor);
        $this->assertGreaterThan(0, $listaErrores->count(), 'El usuario debe ser mayor de edad');
        $error = $listaErrores[0];
        $this->assertEquals('Tienes que ser mayor de edad para registrarte', $error->getMessage());
    }

}
