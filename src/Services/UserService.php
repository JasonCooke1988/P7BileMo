<?php


namespace App\Services;



use Doctrine\ORM\EntityManagerInterface;

class UserService
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
        return $this->entityManager->getRepository('App:User')->search(
            $terms, $orderBy, $order, $limit, $offset, $client
        );
    }

    public function create($user, $client)
    {
        return $this->entityManager->getRepository('App:User')->create(
            $user, $client
        );
    }

    public function delete($user)
    {
        return $this->entityManager->getRepository('App:User')->delete(
            $user
        );
    }

}
