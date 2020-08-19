<?php

namespace App\Form;

use App\Entity\Projects;
use App\Entity\Tasks;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TasksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('id_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
            ->add('id_project', EntityType::class, [
                'class' => Projects::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
        ]);
    }
}
