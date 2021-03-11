<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param string|null $term
     */
    public function getPostsByCategoryId(int $category_id)
    {
        return  $this->createQueryBuilder('p')
                ->andWhere('p.category = :val')
                ->andWhere('p.status = :status')
                ->setParameter('val', $category_id)
                ->setParameter('status', 1);
    }
    
}
