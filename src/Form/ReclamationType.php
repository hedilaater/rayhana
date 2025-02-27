<?php

/*namespace App\Form;

use App\Entity\User;
use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_rec', null, [
                'widget' => 'single_text',
            ])
            ->add('categorie_rec')
            ->add('message_rec')
            ->add('name_rec')
            ->add('email_rec')
            ->add('etat')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}*/

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('categorie_rec', ChoiceType::class, [
            'label' => 'Catégorie',
            'choices' => [
                'Category 1' => 'category_1',
                'Category 2' => 'category_2',
                'Category 3' => 'category_3',
            ],
            'placeholder' => 'Sélectionnez une catégorie',
            'required' => true, 
            'constraints' => [
                    new NotBlank(null, 'Category cannot be empty.'),
                ],
        ])
        ->add('message_rec', TextareaType::class, [
            'label' => 'Message',
            'required' => true,
            'constraints' => [
                    new NotBlank(null, 'Message cannot be empty.'),
                ],
        ])
        ->add('name_rec', TextType::class, [
            'label' => 'Nom',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'The name cannot be empty.'
                ]),
                new Regex([
                    'pattern' => '/^[a-zA-Z\s]+$/', 
                    'message' => 'The name cannot contain numbers or special characters.'
                ])
            ],
        ])
        ->add('email_rec', EmailType::class, [
            'label' => 'Email',
            'required' => true,
            'constraints' => [
                    new NotBlank(null, 'Email cannot be empty.'),
                    new Email(['message' => 'Email {{ value }} is invalid.
                                             A valid email address should look like: example@email.com']),
                ],
        ]);
        
       // ->add('user', EntityType::class, [
         //       'class' => User::class,
           //     'choice_label' => 'id',
             //   'label' => 'Utilisateur',
            //]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
            'method' => 'POST',
            'attr' => [
                'id' => 'form_reclamation',
                'enctype' => 'multipart/form-data'
            ],
        ]);
    }
}
