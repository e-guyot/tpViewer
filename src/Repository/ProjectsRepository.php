<?php

namespace App\Repository;

use App\Entity\Projects;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Projects|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projects|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projects[]    findAll()
 * @method Projects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projects::class);
    }

    public function findUserProject(int $idUser)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p.id id, p.name p_name, g.name g_name
        FROM App\Entity\Projects p
        INNER JOIN App\Entity\UserGroup ug WITH p.id_group = ug.id_group
        INNER JOIN App\Entity\Groups g WITH ug.id_group = g.id
        WHERE ug.id_user = :id'
        )->setParameter('id', $idUser);

        return $query->getResult();
    }
}
