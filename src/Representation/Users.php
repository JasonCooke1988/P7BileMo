<?php


namespace App\Representation;

use JMS\Serializer\Annotation as Serializer;
use Pagerfanta\Pagerfanta;

class Users extends AbstractRepresentation
{
    /**
     * @var Pagerfanta
     * @Serializer\Type("array<App\Entity\User>")
     * @Serializer\Groups({"list","listClient","all"})
     */
    public $data;
}