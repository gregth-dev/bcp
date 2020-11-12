<?php

namespace App\Form\AccountForm;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserFonctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fonction', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'label' => "Choisissez une fonction",
                'choices'  => [
                    'Technicien' => 'Technicien',
                    'Coordinateur' => 'Coordinateur',
                    'RH' => 'RH',
                    'Direction' => 'Direction',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
