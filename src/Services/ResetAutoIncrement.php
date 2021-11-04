<?php


namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;

class ResetAutoIncrement
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function reset(string $entity)
    {
        $this->em->getRepository($entity)->resetAutoIncrement();
    }
}
