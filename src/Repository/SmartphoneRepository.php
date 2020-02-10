<?php

namespace App\Repository;

use App\Entity\Smartphone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Smartphone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Smartphone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Smartphone[]    findAll()
 * @method Smartphone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmartphoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Smartphone::class);
    }

    public function findAllSmartphone(int $page, $filter = '', int $limit = Smartphone::LIMIT_PER_PAGE)
    {
        $query = $this->createQueryBuilder('s')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
        ;

        if ($filter) {
            $query->where('s.name LIKE :filter OR s.os LIKE :filter')
                  ->setParameter('filter', '%' . $filter . '%')
            ;
        }

        $query->getQuery();

        return new Paginator($query);
    }
}
