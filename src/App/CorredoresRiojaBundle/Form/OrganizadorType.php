<?php

namespace App\CorredoresRiojaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\CorredoresRiojaBundle\Form\Transformer\RegistroOrganizadorTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class OrganizadorType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nombre')
                ->add('descripcion')
                ->add('email')
                // Pedimos confirmación de la contraseña
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'Las contraseñas deben coincidir.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options' => array('label' => 'Contraseña'),
                    'second_options' => array('label' => 'Repite contraseña'),
                ));
        if (!$options['is_profile']) {
            // Por último añadimos el botón de envío.
            $builder->add('registro', SubmitType::class, array('label' => 'Registro'));
        } else {
            $builder->add('guardar', SubmitType::class, array('label' => 'Guardar cambios'));
        }
        $builder->addViewTransformer(new RegistroOrganizadorTransformer());
    }

    public function getName() {
        return 'organizador';
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\CorredoresRiojaBundle\Form\DTO\RegistroOrganizadorCommand',
            'error_mapping' => array('passwordLegal' => 'password'),
            'is_profile' => false
        ));
    }

}
