<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ModifierUserType extends AbstractType
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
            ->add('prenom', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']], ['constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un prenom',
                ]),
            ]])
            ->add('adresse', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']], ['constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une adresse',
                ]),
            ]])
            ->add('ville', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']], ['constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir une ville',
                ]),
            ]])
            ->add('cp', TextType::class, ['attr' => ['class' => 'form-control'], 'label_attr' => ['class' => 'fw-bold']], ['constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir un code postal',
                ]),
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
