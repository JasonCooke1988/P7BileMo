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
                'client' => $amazon,
                'adresse' => '1 Rue du chateau',
                'city' => 'Valence',
                'email' => 'jason.cooke@gmail.com',
                'postCode' => '47500'
            ],
            [
                'name' => 'Stella Cooke',
                'client' => $amazon,
                'adresse' => '1 Rue du billeton',
                'city' => 'Lyon',
                'email' => 'stella.cooke@gmail.com',
                'postCode' => '45300'
            ],
            [
                'name' => 'Peter Cooke',
                'client' => $ebay,
                'adresse' => '6 avenue de la boulangerie',
                'city' => 'Agen',
                'email' => 'peter.cooke@gmail.com',
                'postCode' => '63500'
            ],
            [
                'name' => 'Lindsay Cooke',
                'client' => $ebay,
                'adresse' => '40 boulevard des oies',
                'city' => 'St. Etienne',
                'email' => 'lindsay.cooke@gmail.com',
                'postCode' => '87500'
            ],
            [
                'name' => 'Jordan Allesant',
                'client' => $leclerc,
                'adresse' => '1 Rue du chat',
                'city' => 'Aubenas',
                'email' => 'jordan.allesant@gmail.com',
                'postCode' => '90500'
            ],
            [
                'name' => 'Angie Allesant',
                'client' => $leclerc,
                'adresse' => '40 Rue du bord de rivière',
                'city' => 'Bordeaux',
                'email' => 'angie.allesant@gmail.com',
                'postCode' => '47500'
            ],
            [
                'name' => 'Nathalie Vedel',
                'client' => $orange,
                'adresse' => '1 Rue du moulin',
                'city' => 'Paris',
                'email' => 'nathalie.vedel@gmail.com',
                'postCode' => '75500'
            ]
        ];

        foreach ($args as $elt) {
            $user = new User();

            $user->setName($elt['name']);
            $user->setClient($elt['client']);
            $user->setAdresse($elt['adresse']);
            $user->setCity($elt['city']);
            $user->setEmail($elt['email']);
            $user->setPostCode($elt['postCode']);
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