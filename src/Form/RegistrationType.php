<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Votre prénom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom"))
            ->add('email', TextType::class, $this->getConfiguration("Email", "Votre adresse email.."))
            ->add('picture', UrlType::class, $this->getConfiguration("Votre photo de profil", "Url de votre photo"))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Tapez un bon mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer le mot de passe"))
            ->add('introduction',TextType::class, $this->getConfiguration("Introduction", "Présentez-vous en quelques mots"))
            ->add('description', TextareaType::class, $this->getConfiguration("Description détaillée", "C'est le moment de vous présenter"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
