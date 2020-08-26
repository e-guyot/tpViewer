<?php

namespace App\Form;

use App\Entity\Groups;
use App\Entity\Projects;
use App\Entity\UserGroup;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('id_group', EntityType::class, [
                'class' => Groups::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $q = $er->createQueryBuilder('g');
                    $q->innerJoin(UserGroup::class, 'ug', 'WITH', 'g.id = ug.id_group')
                        ->where('ug.id_user = :id')
                        ->setParameter(':id', $options['user']->getId());
                    return $q;
                },
                'choice_label' => 'name'
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
            'user' => null,
        ]);
    }
}
