<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\ResourceValidationException;
use App\Representation\Products;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationList;
use Nelmio\ApiDocBundle\Annotation as Doc;



class ProductController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     path="/products/{id}",
     *     name="app_product_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View()
     */
    public function showAction(Product $product): Product
    {
        return $product;
    }


    /**
     * @Rest\Post(
     *     path="/products",
     *     name="app_product_create"
     * )
     * @Rest\View(statusCode = 201)
     * @ParamConverter(
     *     "product",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={"groups"="Create"}
     *     })
     * @throws ResourceValidationException
     */
    public function createAction(Product $product, ConstraintViolationList $violations)
    {

        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct :';

            foreach ($violations as $violation) {
                $message .= sprintf('Field %s: %s ', $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();

        return $product;
    }

    /**
     * @Rest\Get("/products", name="app_product_list")
     * @Rest\QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]*",
     *     nullable=true,
     *     description="The keyword to search for."
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
     */
    public function listAction(ParamFetcherInterface $paramFetcher): Products
    {

        $pager = $this->getDoctrine()->getRepository('App:Product')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Products($pager);
    }
}