<?php

namespace App\Repository;

use App\Entity\Tasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tasks[]    findAll()
 * @method Tasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tasks::class);
    }

    // /**
    //  * @return Tasks[] Returns an array of Tasks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findProjetTask(int $id)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p.id id, p.name p_name, g.name g_name
        FROM App\Entity\Projects p
        INNER JOIN App\Entity\Tasks ug WITH p.id_group = ug.id_group
        WHERE ug.id_user = :id'
        )->setParameter('id', $id);

        return $query->getResult();
    }

    public function findTasks($value)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT t.id id, t.timer timer, t.date_start dateStart, t.date_end dateEnd, t.name name, u.username username
        FROM App\Entity\Tasks t
        INNER JOIN App\Entity\Projects p WITH p.id = t.id_project
        INNER JOIN App\Entity\User u WITH u.id = t.id_user
        WHERE t.id_project = :id'
        )->setParameter('id', $value);

        return $query->getResult();
    }

    public function findTask($value)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT t.id id, t.timer timer, t.date_start dateStart, t.date_end dateEnd, t.name name
        FROM App\Entity\Tasks t
        WHERE t.id = :id'
        )->setParameter('id', $value);

        return $query->getResult();
    }

    public function timeProjectUser($idUser)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT SUM(t.timer) y, p.name label
            FROM App\Entity\Tasks t
            INNER JOIN App\Entity\Projects p WITH p.id = t.id_project
            WHERE t.id_user = :id
            GROUP BY label'
        )->setParameter('id', $idUser);

        return $query->getResult();
    }

    public function getTasks($idProject)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT t
        FROM App\Entity\Tasks t
        WHERE t.id_project = :id'
        )->setParameter('id', $idProject);

        $tasks = array_map(function ($value) {
            return (object) $value;
        }, $query->getResult());

        return $tasks;
    }

}
