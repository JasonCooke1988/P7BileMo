<?php


namespace App\Representation;

use JMS\Serializer\Annotation as Serializer;
use Pagerfanta\Pagerfanta;
class Products extends AbstractRepresentation
{
    /**
     * @var Pagerfanta
     * @Serializer\Type("array<App\Entity\Product>")
     * @Serializer\Groups({"list"})
     */
    public $data;
}