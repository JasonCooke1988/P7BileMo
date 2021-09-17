<?php


namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractRepository extends ServiceEntityRepository
{

    protected function paginate(Array $array, $limit = 20, $offset = 0)
    {
        if (0 == $limit || 0 == $offset) {
            throw new \LogicException('$limit & $offset mst be greater than 0.');
        }

        $pager = new Pagerfanta(new ArrayAdapter($array));
        $currentPage = ceil(($offset + 1) / $limit);
        $pager->setCurrentPage($currentPage);
        $pager->setMaxPerPage((int) $limit);

        return $pager;

    }

}