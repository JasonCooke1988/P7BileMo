<?php

namespace App\Controller;

use App\Representation\Products;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;

class ProductController extends AbstractFOSRestController
{

    /**
     * @Rest\Get("/api/products", name="app_product_show")
     * @Rest\QueryParam(
     *     name="name",
     *     requirements="[a-zA-Z 0-9]+",
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
     *     name="min_price",
     *     requirements="\d+",
     *     description="Products mast have a price of minimum this value."
     * )
     * @Rest\QueryParam(
     *     name="max_price",
     *     requirements="\d+",
     *     description="Products mast have a price of maximum this value."
     * )
     * @Rest\QueryParam(
     *     name="price",
     *     requirements="\d+",
     *     description="The price to search for."
     * )
     * @Rest\QueryParam(
     *     name="min_stock",
     *     requirements="\d+",
     *     description="Products mast have a stock of minimum this value."
     * )
     * @Rest\QueryParam(
     *     name="max_stock",
     *     requirements="\d+",
     *     description="Products mast have a stock of maximum this value."
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of products per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination offset"
     * )
     * @Rest\View(statusCode = 200)
     */
    public function showAction(ParamFetcherInterface $paramFetcher): Products
    {

        $keywords = array(
            'name' => $paramFetcher->get('name'),
            'price' => $paramFetcher->get('price'),
            'min_price' => $paramFetcher->get('min_price'),
            'max_price' => $paramFetcher->get('max_price'),
            'min_stock' => $paramFetcher->get('min_stock'),
            'max_stock' => $paramFetcher->get('max_stock'),
        );

        $pager = $this->getDoctrine()->getRepository('App:Product')->search(
            $keywords,
            $paramFetcher->get('order_by'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Products($pager);
    }
}