<?php


namespace App\Controller;


use App\Entity\User;
use App\Representation\Users;
use App\Token\ClientService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

class UserController extends AbstractFOSRestController
{
    /**
     * @var ClientService
     */
    private $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @Rest\Get("/api/users", name="app_user_list")
     * @Rest\QueryParam(
     *     name="name",
     *     requirements="[a-zA-Z0-9]*",
     *     nullable=true,
     *     description="The name to search for."
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of movies per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination offset"
     * )
     * @Rest\View()
     * @param ParamFetcherInterface $paramFetcher
     * @return Users
     */
    public function listAction(ParamFetcherInterface $paramFetcher): Users
    {
        $keywords = array(
            'name' => $paramFetcher->get('name')
        );

        $client = $this->clientService->getCurrentClient();

        $pager = $this->getDoctrine()->getRepository('App:User')->search(
            $keywords,
            $paramFetcher->get('order_by'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Users($pager);
    }

}