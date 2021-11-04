<?php


namespace App\Controller;


use App\Entity\Client;
use App\Entity\User;
use App\Exception\ResourceValidationException;
use App\Representation\Users;
use App\Services\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class UserController extends AbstractFOSRestController
{


    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Rest\Get(path="/api/users/{id}",name="app_user_show",requirements={"id"="\d+"})
     * @Rest\View(statusCode = 200, serializerGroups={"detail"})
     * @Cache(lastModified="user.getUpdatedAt()", Etag="'User' ~ user.getId() ~ user.getUpdatedAt()")
     * @OA\Response(
     *     response=200,
     *     description="Returns the requested user",
     *     @Model(type=User::class, groups={"detail"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="Access Denied"
     * )
     * @OA\Tag(name="User")
     */
    public function showAction(User $user): User
    {
        return $user;
    }

    /**
     * @Rest\Get("/api/users", name="app_user_list")
     * @Rest\QueryParam(name="name",requirements="[a-zA-Z 0-9]*",nullable=true,description="The name to search for.")
     * @Rest\QueryParam(name="adresse",requirements="[a-zA-Z 0-9]*",nullable=true,description="The adresse to search for.")
     * @Rest\QueryParam(name="city",requirements="[a-zA-Z 0-9]*",nullable=true,description="The city to search for.")
     * @Rest\QueryParam(name="email",requirements="[a-zA-Z 0-9]*",nullable=true,description="The email to search for.")
     * @Rest\QueryParam(name="post_code",requirements="[a-zA-Z 0-9]*",nullable=true,description="The post code to search for.")
     * @Rest\QueryParam(name="order",requirements="asc|desc",default="asc",description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="order_by",requirements="[a-zA-Z 0-9]+",default="name",description="Sort order by this value.")
     * @Rest\QueryParam(name="limit",requirements="\d+",default="15",description="Max number of users per page.")
     * @Rest\QueryParam(name="offset",requirements="\d+",default="1",description="The pagination offset")
     * @Rest\View(statusCode = 200, serializerGroups={"list"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user list",
     *     @Model(type=User::class, groups={"list"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="Access Denied"
     * )
     * @OA\Tag(name="User")
     * @param ParamFetcherInterface $paramFetcher
     * @return Users
     */
    public function listAction(ParamFetcherInterface $paramFetcher): Users
    {
        $keywords = array(
            'name' => $paramFetcher->get('name'),
            'adresse' => $paramFetcher->get('adresse'),
            'city' => $paramFetcher->get('city'),
            'email' => $paramFetcher->get('email'),
            'postCode' => $paramFetcher->get('post_code'),
        );
        $pager = $this->userService->search(
            $keywords,
            $paramFetcher->get('order_by'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Users($pager);
    }

    /**
     * @Rest\Get("/api/client/{id}/users", name="app_client_user_list")
     * @Rest\QueryParam(name="name",requirements="[a-zA-Z 0-9]*",nullable=true,description="The name to search for.")
     * @Rest\QueryParam(name="adresse",requirements="[a-zA-Z 0-9]*",nullable=true,description="The adresse to search for.")
     * @Rest\QueryParam(name="city",requirements="[a-zA-Z 0-9]*",nullable=true,description="The city to search for.")
     * @Rest\QueryParam(name="email",requirements="[a-zA-Z 0-9]*",nullable=true,description="The email to search for.")
     * @Rest\QueryParam(name="post_code",requirements="[a-zA-Z 0-9]*",nullable=true,description="The post code to search for.")
     * @Rest\QueryParam(name="order",requirements="asc|desc",default="asc",description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="order_by",requirements="[a-zA-Z 0-9]+",default="name",description="Sort order by this value.")
     * @Rest\QueryParam(name="limit",requirements="\d+",default="15",description="Max number of users per page.")
     * @Rest\QueryParam(name="offset",requirements="\d+",default="1",description="The pagination offset")
     * @Rest\View(statusCode = 200, serializerGroups={"listClient"})
     * @OA\Response(
     *     response=200,
     *     description="Returns a User list associated to the requested Client",
     *     @Model(type=User::class, groups={"listClient"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="Access Denied"
     * )
     * @OA\Tag(name="User")
     * @param Client $client
     * @param ParamFetcherInterface $paramFetcher
     * @return Users
     */
    public function listByClientAction(Client $client, ParamFetcherInterface $paramFetcher): Users
    {

        $keywords = array(
            'name' => $paramFetcher->get('name'),
            'adresse' => $paramFetcher->get('adresse'),
            'city' => $paramFetcher->get('city'),
            'email' => $paramFetcher->get('email'),
            'postCode' => $paramFetcher->get('post_code'),
        );
        $pager = $this->userService->search(
            $keywords,
            $paramFetcher->get('order_by'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset'),
            $client
        );
        return new Users($pager);
    }


    /**
     * @Rest\Post(
     *     path="/api/client/{id}/users/create",
     *     name="app_user_create",
     *     requirements={"id"="\d+"}
     * )
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={"groups"="Create"}
     *     })
     * @Rest\View(statusCode = 201, serializerGroups={"all"})
     * @OA\Response(
     *     response=201,
     *     description="Returns the newly created User",
     *     @Model(type=User::class, groups={"listClient"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="Access Denied"
     * )
     * @OA\Tag(name="User")
     * @throws ResourceValidationException
     */
    public function createAction(Client $client, User $user, ConstraintViolationList $violations): User
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct :';

            foreach ($violations as $violation) {
                $message .= sprintf('Field %s: %s ', $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }
        return $this->userService->create($user, $client);
    }

    /**
     * @Rest\Delete(
     *     path="/api/users/delete/{id}",
     *     name="app_user_delete",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(statusCode = 200)
     * @OA\Response(
     *     response=200,
     *     description="Returns a confirmation message that the User has been deleted"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Access Denied"
     * )
     * @OA\Tag(name="User")
     */
    public function deleteAction(User $user): Response
    {
        $view = View::create()->setData(['message' => sprintf("User with id '%s' has been deleted.", $user->getId())]);

        $this->userService->delete($user);

        return $this->getViewHandler()->handle($view);
    }

}
