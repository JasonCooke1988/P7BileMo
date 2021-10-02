<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $amazon = $this->getReference(ClientFixtures::AMAZON_CLIENT_REFERENCE);
        $ebay = $this->getReference(ClientFixtures::EBAY_CLIENT_REFERENCE);
        $leclerc = $this->getReference(ClientFixtures::LECLERC_CLIENT_REFERENCE);
        $orange = $this->getReference(ClientFixtures::ORANGE_CLIENT_REFERENCE);

        $args = [
            [
                'name' => 'Jason Cooke',
                'client' => $amazon
            ],
            [
                'name' => 'Stella Cooke',
                'client' => $amazon
            ],
            [
                'name' => 'Peter Cooke',
                'client' => $ebay
            ],
            [
                'name' => 'Lindsay Cooke',
                'client' => $ebay
            ],
            [
                'name' => 'Jordan Allesant',
                'client' => $leclerc
            ],
            [
                'name' => 'Angie Allesant',
                'client' => $leclerc
            ],
            [
                'name' => 'Nathalie Vedel',
                'client' => $orange
            ]
        ];

        foreach ($args as $elt) {
            $user = new User();

            $user->setName($elt['name']);
            $user->setClient($elt['client']);
            $user->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClientFixtures::class
        ];
    }
}