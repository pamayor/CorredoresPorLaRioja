<?php

namespace App\CorredoresRiojaBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use App\CorredoresRiojaBundle\Form\DTO\RegistroOrganizadorCommand;
use App\CorredoresRiojaDomain\Model\Organizacion;

class RegistroOrganizadorTransformer implements DataTransformerInterface {

    public function reverseTransform($value) {
        $organizacion = new Organizacion($value->getId(), $value->getNombre(), $value->getDescripcion(), $value->getEmail(), $value->getPassword());
        return $organizacion;
    }

    public function transform($value) {
        if ($value === null) {
            return null;
        }
        $registroOrganizadorCommand = new RegistroOrganizadorCommand();
        $registroOrganizadorCommand->setId($value->getId());
        $registroOrganizadorCommand->setNombre($value->getNombre());
        $registroOrganizadorCommand->setDescripcion($value->getDescripcion());
        $registroOrganizadorCommand->setEmail($value->getEmail());
        $registroOrganizadorCommand->setPassword($value->getPassword());
        return $registroOrganizadorCommand;
    }

}
