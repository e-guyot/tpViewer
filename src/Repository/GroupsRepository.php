<?php

namespace App\Repository;

use App\Entity\Groups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Groups|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groups|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groups[]    findAll()
 * @method Groups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groups::class);
    }

    public function findUserGroup ($idUser) {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT g.id, g.name
        FROM App\Entity\Groups g
        INNER JOIN App\Entity\UserGroup ug WITH g.id = ug.id_group
        WHERE ug.id_user = :id'
        )->setParameter('id', $idUser);

        return $query->getResult();
    }

    public function checkGroupProjects ($idGroup) {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT g.name, p.name
        FROM App\Entity\Groups g
        INNER JOIN App\Entity\Projects p WITH g.id = p.id_group
        WHERE g.id = :id'
        )->setParameter('id', $idGroup);

        return $query->getResult();
    }
}
