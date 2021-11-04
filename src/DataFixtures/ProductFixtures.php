<?php


namespace App\DataFixtures;


use App\Entity\Product;
use App\Services\ResetAutoIncrement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    /**
     * @var ResetAutoIncrement
     */
    private $resetAutoIncrement;

    public function __construct(ResetAutoIncrement $resetAutoIncrement)
    {
        $this->resetAutoIncrement = $resetAutoIncrement;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $this->resetAutoIncrement->reset('App:Product');

        $args = [
            [
                'name' => 'Iphone 1',
                'price' => 100.00,
                'description' => 'Iphone 1 by Apple',
                'stock' => 800
            ],
            [
                'name' => 'Iphone 2',
                'price' => 200.00,
                'description' => 'Iphone 2 by Apple',
                'stock' => 700
            ],
            [
                'name' => 'Iphone 3',
                'price' => 300.00,
                'description' => 'Iphone 3 by Apple',
                'stock' =>600
            ],
            [
                'name' => 'Iphone 4',
                'price' => 400.00,
                'description' => 'Iphone 4 par Apple',
                'stock' => 500
            ],
            [
                'name' => 'Iphone 5',
                'price' => 500.00,
                'description' => 'Iphone 5 par Apple',
                'stock' => 400
            ],
            [
                'name' => 'Iphone 6',
                'price' => 600.00,
                'description' => 'Iphone 6 par Apple',
                'stock' => 300
            ],
            [
                'name' => 'Iphone 6 plus',
                'price' => 650.00,
                'description' => 'Iphone 6 par Apple',
                'stock' => 200
            ],
            [
                'name' => 'Iphone 7',
                'price' => 700.00,
                'description' => 'Iphone 7 par Apple',
                'stock' => 100
            ]
        ];

        foreach ($args as $elt) {
            $product = new Product();

            $product->setName($elt['name']);
            $product->setPrice($elt['price']);
            $product->setDescription($elt['description']);
            $product->setStock($elt['stock']);
            $product->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($product);
        }

        $manager->flush();
    }
}
