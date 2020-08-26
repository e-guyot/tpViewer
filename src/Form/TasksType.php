<?php

namespace App\Form;

use App\Entity\Groups;
use App\Entity\Projects;
use App\Entity\Tasks;
use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\ORM\EntityRepository;
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
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $q = $er->createQueryBuilder('u');
                    $q->innerJoin(UserGroup::class, 'ug', 'WITH', 'u.id = ug.id_user')
                        ->innerJoin(Projects::class, 'p', 'WITH', 'p.id_group = ug.id_group')
                        ->where('p.id = :id')
                        ->setParameter(':id', $options['project']->getId());
                    return $q;
                },
                'choice_label' => 'username'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
            'project' => null,
        ]);
    }
}
