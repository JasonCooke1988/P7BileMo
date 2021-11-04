<?php


namespace App\DataFixtures;


use App\Entity\Client;
use App\Services\ResetAutoIncrement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixtures extends Fixture
{

    public const AMAZON_CLIENT_REFERENCE = "amazon-client";
    public const EBAY_CLIENT_REFERENCE = "ebay-client";
    public const LECLERC_CLIENT_REFERENCE = "leclerc-client";
    public const ORANGE_CLIENT_REFERENCE = "orange-client";

    private $passwordHasher;
    /**
     * @var ResetAutoIncrement
     */
    private $resetAutoIncrement;


    public function __construct(UserPasswordHasherInterface $passwordHasher, ResetAutoIncrement $resetAutoIncrement)
    {
        $this->passwordHasher = $passwordHasher;
        $this->resetAutoIncrement = $resetAutoIncrement;
    }


    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $this->resetAutoIncrement->reset('App:Client');

        $args = [
            [
                'name' => 'Amazon',
                'ref' => self::AMAZON_CLIENT_REFERENCE
            ],
            [
                'name' => 'Ebay',
                'ref' => self::EBAY_CLIENT_REFERENCE
            ],
            [
                'name' => 'Leclerc',
                'ref' => self::LECLERC_CLIENT_REFERENCE
            ],
            [
                'name' => 'Orange',
                'ref' => self::ORANGE_CLIENT_REFERENCE
            ]
        ];

        foreach($args as $elt) {
            $client = new Client();

            $client->setUsername($elt['name']);
            $client->setPassword($this->passwordHasher->hashPassword($client,'test'));
            $client->setCreatedAt(new \DateTimeImmutable());
            $this->addReference($elt['ref'], $client);

            $manager->persist($client);
        }

        $manager->flush();
    }

}
