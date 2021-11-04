<?php


namespace App\Services;



use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function search($terms, $orderBy, $order, $limit, $offset, $client = null)
    {
        return $this->em->getRepository('App:Product')->search(
            $terms, $orderBy, $order, $limit, $offset, $client
        );
    }
}
