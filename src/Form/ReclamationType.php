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
        ])
        ->add('message_rec', TextareaType::class, [
            'label' => 'Message',
            'required' => true, // Ensures the field is required
        ])
        ->add('name_rec', TextType::class, [
            'label' => 'Nom',
            'required' => true, // Makes the field mandatory
        ])
        ->add('email_rec', EmailType::class, [
            'label' => 'Email',
            'required' => true, // Ensures a valid email format is entered
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
        ]);
    }
}
