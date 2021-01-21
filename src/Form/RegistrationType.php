<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Prenom:'
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Nom:'
            ])
            ->add('mail', TextType::class,[
                'label' => 'Email:'
            ])
            ->add('hash', PasswordType::class,[
                'label' => 'Mot de passe:'
            ])
            ->add('passwordConfirm', PasswordType::class, [
                'label' => 'Confirmation du mot de passe:'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
