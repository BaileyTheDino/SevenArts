<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tab>
 */
final class TabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tab::class);
    }

    public function save(Tab $tab, bool $flush = true): void
    {
        $this->getEntityManager()->persist($tab);

        if ($flush === true) {
            $this->getEntityManager()->flush();
        }
    }
}
