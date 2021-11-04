<?php

namespace App\Controller;

use App\Entity\Product;
use App\Representation\Products;
use App\Services\ProductService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class ProductController extends AbstractFOSRestController
{

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Rest\Get(path="/api/products/{id}",name="app_product_show",requirements={"id"="\d+"})
     * @Rest\View(statusCode = 200, serializerGroups={"detail"})
     * @Cache(lastModified="product.getUpdatedAt()", Etag="'Product' ~ product.getId() ~ product.getUpdatedAt()")
     * @OA\Response(
     *     response=200,
     *     description="Returns a User list associated to the requested Client",
     *     @Model(type=Product::class, groups={"detail"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="Access Denied"
     * )
     * @OA\Tag(name="Product")
     */
    public function showAction(Product $product): Product
    {
        return $product;
    }

    /**
     * @Rest\Get("/api/products", name="app_product_list")
     * @Rest\QueryParam(name="name",requirements="[a-zA-Z 0-9]+",nullable=true,description="The name to search for.")
     * @Rest\QueryParam(name="order",requirements="asc|desc",default="asc",description="Sort order (asc or desc)")
     * @Rest\QueryParam(name="order_by",requirements="[a-zA-Z 0-9]+",default="name",description="Sort order by this value.")
     * @Rest\QueryParam(name="min_price",requirements="\d+",description="Products mast have a price of minimum this value.")
     * @Rest\QueryParam(name="max_price",requirements="\d+",description="Products mast have a price of maximum this value.")
     * @Rest\QueryParam(name="price",requirements="\d+",description="The price to search for.")
     * @Rest\QueryParam(name="min_stock",requirements="\d+",description="Products mast have a stock of minimum this value.")
     * @Rest\QueryParam(name="max_stock",requirements="\d+",description="Products mast have a stock of maximum this value.")
     * @Rest\QueryParam(name="limit",requirements="\d+",default="15",description="Max number of products per page.")
     * @Rest\QueryParam(name="offset",requirements="\d+",default="1",description="The pagination offset")
     * @Rest\View(statusCode = 200, serializerGroups={"list"})
     * @OA\Response(
     *     response=200,
     *     description="Returns a User list associated to the requested Client",
     *     @Model(type=Product::class, groups={"list"})
     * )
     * @OA\Response(
     *     response=400,
     *     description="Access Denied"
     * )
     * @OA\Tag(name="Product")
     * @param ParamFetcherInterface $paramFetcher
     * @return Products
     */
    public function listAction(ParamFetcherInterface $paramFetcher): Products
    {

        $keywords = array(
            'name' => $paramFetcher->get('name'),
            'price' => $paramFetcher->get('price'),
            'min_price' => $paramFetcher->get('min_price'),
            'max_price' => $paramFetcher->get('max_price'),
            'min_stock' => $paramFetcher->get('min_stock'),
            'max_stock' => $paramFetcher->get('max_stock'),
        );

        $pager = $this->productService->search(
            $keywords,
            $paramFetcher->get('order_by'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return new Products($pager);
    }
}
