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
        $queryBuilder = $this
            ->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.' . $orderBy, $order);

        $count = 0;
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

                if ($count === 0) {
                    $queryBuilder
                        ->where('e.' . $key . ' ' . $comparaison . ' ?' . $count);
                } else {
                    $queryBuilder
                        ->andWhere('e.' . $key . ' ' . $comparaison . ' ?' . $count);
                }

                $queryBuilder->setParameter($count, $like . $term . $like);
                $count++;
            }
        }

        /** @var Client $client */
        if (is_a($client, Client::class) && $count === 0) {
            $queryBuilder->where('e.client = :c')
                ->setParameter('c', $client);
        } elseif (is_a($client, Client::class) && $count != 0) {
            $queryBuilder->andWhere('e.client = :c')
                ->setParameter('c', $client);
        }

        $array = $queryBuilder->getQuery()->getResult();

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

    public function delete($elt)
    {
        $queryBuilder = $this->createQueryBuilder("e")->delete()->where('e.id = :id')->setParameter('id', $elt->getId());
        $queryBuilder->getQuery()->getResult();
    }

    public function resetAutoIncrement()
    {
        $array = explode('\\', $this->_entityName);
        $entity = end($array);
        $this->_em->getConnection()->executeQuery('ALTER TABLE '.$entity.' AUTO_INCREMENT = 1;');
    }

}
