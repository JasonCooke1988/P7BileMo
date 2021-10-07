<?php


namespace App\Repository;


use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractRepository extends ServiceEntityRepository
{

    public function search($terms, $orderBy, $order, $limit, $offset, $client = null): Pagerfanta
    {
        $qb = $this
            ->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.' . $orderBy, $order);

        $i = 0;
        $like = '';

        foreach ($terms as $key => $term) {

            if ($term) {
                switch ($key) {
                    case 'min_price':
                    case 'min_stock':
                        $key = str_replace('min_', '', $key);
                        $comparaison = '>=';
                        break;
                    case 'max_price':
                    case 'max_stock':
                        $key = str_replace('max_', '', $key);
                        $comparaison = '<=';
                        break;
                    default:
                        $comparaison = 'LIKE';
                        $like = '%';
                        break;
                }

                if ($i === 0) {
                    $qb
                        ->where('e.' . $key . ' ' . $comparaison . ' ?' . $i);
                } else {
                    $qb
                        ->andWhere('e.' . $key . ' ' . $comparaison . ' ?' . $i);
                }

                $qb->setParameter($i, $like . $term . $like);
                $i++;
            }
        }

        /** @var Client $client */
        if (is_a($client, Client::class) && $i === 0) {
            $qb->where('e.client = :c')
                ->setParameter('c', $client);
        } elseif (is_a($client, Client::class) && $i != 0) {
            $qb->andWhere('e.client = :c')
                ->setParameter('c', $client);
        }

        $array = $qb->getQuery()->getResult();

        return $this->paginate($array, $limit, $offset);
    }


    protected function paginate(array $array, $limit = 20, $offset = 0): Pagerfanta
    {

        if (0 == $limit || 0 == $offset) {
            throw new \LogicException('$limit & $offset mst be greater than 0.');
        }

        $pager = new Pagerfanta(new ArrayAdapter($array));
        $currentPage = ceil(($offset + 1) / $limit);

        $pager->setMaxPerPage((int)$limit);
        $pager->setCurrentPage($currentPage);

        return $pager;
    }

}