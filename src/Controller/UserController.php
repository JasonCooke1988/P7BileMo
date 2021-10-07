<?php


namespace App\Controller;


use App\Entity\User;
use App\Exception\ResourceValidationException;
use App\Representation\Users;
use App\Token\ClientService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationList;

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
     * @Rest\Get(path="/api/users/{id}",
     *     name="app_user_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(statusCode = 200, serializerGroups={"list"})
     */
    public function showAction(User $user)
    {
        $client = $this->clientService->getCurrentClient();

        if ($client->getId() === $user->getClient()->getId()) {
            return $user;
        } else {
            throw new NotFoundHttpException("You are not authorized to delete this user.");
        }
    }

    /**
     * @Rest\Get("/api/users", name="app_user_list")
     * @Rest\QueryParam(
     *     name="name",
     *     requirements="[a-zA-Z 0-9]*",
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
     *     name="order_by",
     *     requirements="[a-zA-Z 0-9]+",
     *     default="name",
     *     description="Sort order by this value."
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of users per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination offset"
     * )
     * @Rest\View(statusCode = 200, serializerGroups={"list"})
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
            $paramFetcher->get('offset'),
            $client
        );

        return new Users($pager);
    }

    /**
     * @Rest\Post(
     *     path="/api/users/create",
     *     name="app_user_create"
     * )
     * @Rest\View(statusCode = 201)
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={"groups"="Create"}
     *     })
     * @throws ResourceValidationException
     */
    public function createAction(User $user, ConstraintViolationList $violations)
    {

        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct :';

            foreach ($violations as $violation) {
                $message .= sprintf('Field %s: %s ', $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $client = $this->clientService->getCurrentClient();

        $user->setCreatedAt(new \DateTimeImmutable('now'));
        $user->setClient($client);

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Rest\Delete(
     *     path="/api/users/delete/{id}",
     *     name="app_user_delete",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(statusCode = 200)
     */
    public function deleteAction(User $user, ParamFetcherInterface $paramFetcher)
    {

        $client = $this->clientService->getCurrentClient();

        if ($client->getId() === $user->getClient()->getId()) {

            $view = View::create()->setData(['message' => sprintf("User with id '%s' has been deleted.", $user->getId())]);

            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            return $this->getViewHandler()->handle($view);
        } else {
            throw new NotFoundHttpException("You are not authorized to delete this user.");
        }
    }

}