<?php

namespace App\Form\AccountForm;

use App\Entity\PasswordUpdate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => "Mot de passe actuel",
                'attr' => [
                    'class' => 'validate'
                ]
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => "Nouveau mot de passe",
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => "Confirmer le mot de passe",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PasswordUpdate::class,
        ]);
    }
}
