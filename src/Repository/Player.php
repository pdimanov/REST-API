<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class Player extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllPlayersQB()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC');
    }

    /**
     * @param string $country
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllPlayersByCountryQB(string $country)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.country = :country')
            ->setParameter('country', $country)
            ->orderBy('p.id', 'ASC');
    }
}