<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserCollectionRepository
 */
class UserCollectionRepository extends EntityRepository
{
    /**
     * Find collection entries for a user
     *
     * @param int $userId
     * @return array
     */
    public function findByUser($userId)
    {
        return $this->createQueryBuilder('uc')
            ->where('uc.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('uc.dateAdded', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Check if user owns a specific pack
     *
     * @param int $userId
     * @param int $packId
     * @return bool
     */
    public function userOwnsPack($userId, $packId)
    {
        $result = $this->createQueryBuilder('uc')
            ->where('uc.user = :userId')
            ->andWhere('uc.pack = :packId')
            ->setParameter('userId', $userId)
            ->setParameter('packId', $packId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result !== null;
    }
}
