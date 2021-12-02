<?php


namespace App\Services;



use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function search($terms, $orderBy, $order, $limit, $offset, $client = null)
    {
        return $this->entityManager->getRepository('App:Product')->search(
            $terms, $orderBy, $order, $limit, $offset, $client
        );
    }
}
