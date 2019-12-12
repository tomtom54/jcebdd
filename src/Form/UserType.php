<?php 
// src/Form/ArticleType.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder   
            ->add('fullName', TextareaType::class/*, [
                'required' => true,
                'constraints' => [new Length(['min'=>1],['max'<=255])]
            ]*/)
            ->add('username', TextareaType::class)
            ->add('email', TextareaType::class)
            ->add('password', TextareaType::class)
            /*->add('role', ChoiceType::class, [
                'choices'  => [
                    'Choisir' => 0,
                    'Utilisateur' => 1,
                    'Administrateur' => 2,
                ],                
            ])*/
            ->add('roles', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class , array(
                'class' => 'AppBundle\Entity\Role', 
                'multiple' => true,))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}