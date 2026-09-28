<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']],
                ['constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse email.',
                    ]),
                    new Email([
                        'message' => 'Cet email n\'est pas valide.',
                    ]),
                ]])
            ->add('nom', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']],
                ['constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nom',
                    ]),
                ]])
            ->add('prenom', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']],['constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un prenom',
                    ]),
                ]])
            ->add('adresse', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']],['constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une adresse',
                ]),
            ]])
            ->add('ville', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']],['constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une ville',
                ]),
            ]])
            ->add('cp', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']],['constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un code postal',
                ]),
            ]])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'data' => false,
                'constraints' => [
                    new IsTrue(
                        message: 'Tu dois accepter les termes d\'utilisation.',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label_attr' => ['class' => 'fw-bold'],
                'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter a password',
                    ),
                    new Length(
                        min: 10,
                        minMessage: 'Veuillez mettre le nombre de caractère requis: 10',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                    new Assert\Regex(
                        pattern: '/[^a-zA-Z0-9]/',
                        message: 'Veuillez mettre un caractère spécial comme par exemple: !, @, #, ?'
                    ),
                    new Assert\Regex(
                        pattern: '/\d/',
                        message: 'Veuillez mettre au minimum un chiffre'
                    ),
                    new Assert\Regex(
                        pattern: '/[a-z]/',
                        message: 'Veuillez mettre au minimum un caractère en minuscule'
                    ),
                    new Assert\Regex(
                        pattern: '/[A-Z]/',
                        message: 'Veuillez mettre au minimum un caractère en majuscule'
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
