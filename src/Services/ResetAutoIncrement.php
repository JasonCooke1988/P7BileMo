<?php


namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;

class ResetAutoIncrement
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function reset(string $entity)
    {
        $this->entityManager->getRepository($entity)->resetAutoIncrement();
    }
}
