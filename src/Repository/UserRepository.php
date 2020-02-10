<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $page
     * @param Customer $customer
     * @param string $filter
     * @param int $limit
     * @return Paginator
     */
    public function findAllUser(int $page, Customer $customer, $filter = '', int $limit = User::LIMIT_PER_PAGE)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.customer = :customer')
            ->setParameter('customer', $customer)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
        ;

        if ($filter) {
            $query->andWhere('u.email LIKE :filter OR u.slug LIKE :filter')
                  ->setParameter('filter', '%' . $filter . '%')
            ;
        }

        $query->getQuery();

        return new Paginator($query);
    }

    /**
     * @param Customer $customer
     * @return array
     */
    public function findByCustomer(Customer $customer): array
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.customer = :customer')
            ->setParameter('customer', $customer)
            ->getQuery()
        ;

        return $query->getResult();
    }
}
