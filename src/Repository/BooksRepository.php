<?php

namespace App\Repository;

use App\Entity\Books;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Books|null find($id, $lockMode = null, $lockVersion = null)
 * @method Books|null findOneBy(array $criteria, array $orderBy = null)
 * @method Books[]    findAll()
 * @method Books[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Books::class);
    }


    public function customFilter(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()
            ->select('b')
            ->from('App\Entity\Books', 'b');

        if (array_key_exists('authors', $criteria)) {
            $qb = $qb->innerJoin('b.authors', 'a');
        }

        foreach ($criteria as $key => $value) {
            if ($key == 'title'){
                $qb = $qb->andWhere( $qb->expr()->like('b.title', ':value'))
                    ->setParameter('value','%'.$value.'%');
            } elseif ($key == 'description') {
                $qb = $qb->andWhere( $qb->expr()->like('b.description', ':description'))
                    ->setParameter('description','%'.$value.'%');
            } elseif ($key == 'authors') {
                $qb = $qb->andwhere('a.name = :nameParam')
                    ->setParameter('nameParam', $value);
            } elseif ($key == 'year') {
                $qb = $qb->andWhere($qb->expr()->eq('b.'.$key, $value));
            }
        }

        return $qb->getQuery()->getResult();
    }


    public function Get2Authors()
    {
        $em = $this->getEntityManager();
        $result = $em->createQueryBuilder()
            ->select('b')
            ->from('App\Entity\Books', 'b')
            ->join('b.authors', 'ab')
            ->groupBy('b.id')
            ->having('COUNT(b.id) > 2')
            ->getQuery()->getResult();

        return $result;
    }
}

    // /**
    //  * @return Books[] Returns an array of Books objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Books
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
