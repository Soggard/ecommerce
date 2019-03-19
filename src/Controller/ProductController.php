<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProductController extends FOSRestController
{
    /**
     * @FOSRest\Get("/api/products")
     *
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function getProductsAction(ObjectManager $manager)
    {
        $productRepository = $manager->getRepository(Product::class);
        $products = $productRepository->findAll();

        return $this->json($products, Response::HTTP_OK);
    }

    /**
     * @FOSRest\Get("/api/products/{id}")
     *
     * @param ObjectManager $manager
     * @param $id
     *
     * @return Response
     */
    public function getProductAction(ObjectManager $manager, $id)
    {
        $productRepository = $manager->getRepository(Product::class);
        $products = $productRepository->find($id);

        if (!$products instanceof  Product) {
            $this->json([
                'success' => false,
                'error' => 'Product not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($products, Response::HTTP_OK);
    }



    /**
     * @FOSRest\Post("/api/products")
     *
     * @ParamConverter("product", converter="fos_rest.request_body")
     *
     * @param ObjectManager $manager
     * @param Product $product
     * @param ValidatorInterface $validator
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function postProductAction(Product $product, ObjectManager $manager, ValidatorInterface $validator)
    {
        $errors = $validator->validate($product);

        if (!count($errors)) {
            $manager->persist($product);
            $manager->flush();

            return $this->json($product, Response::HTTP_CREATED);
        } else {
            return $this->json([
                'success' => false,
                'error' => $errors[0]->getMessage() . ' (' . $errors[0]->getPropertyPath(). ')'
            ], Response::HTTP_BAD_REQUEST);
        }
    }



    /**
     * @FOSRest\Delete("/api/products/{id}")
     *
     * @param ObjectManager $manager
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteProductAction(ObjectManager $manager, $id)
    {
        $productRepository = $manager->getRepository(Product::class);
        $product = $productRepository->find($id);

        if ($product instanceof Product) {
            $manager->remove($product);
            $manager->flush();

            return $this->json([
                'success' => true
            ], Response::HTTP_OK);
        } else {
            return $this->json([
                'success' => false,
                'error' => 'Product not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }



    /**
     * @FOSRest\Put("/api/products/{id}")
     *
     * @ParamConverter("product", converter="fos_rest.request_body")
     *
     * @param ObjectManager $manager
     * @param $id
     * @param Product $product
     * @param ValidatorInterface $validator
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function putProductAction(Product $product, ObjectManager $manager, $id, ValidatorInterface $validator)
    {
        $productRepository = $manager->getRepository(Product::class);
        $savedProduct = $productRepository->find($id);

        if ( $product instanceof Product) {
            return $this->json([
                'success' => false,
                'error' => 'Product not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $errors = $validator->validate($product);

        if (!count($errors) ) {
            $savedProduct.setName($product.getName());
            $manager->persist($savedProduct);
            $manager->flush();

            return $this->json($product, Response::HTTP_CREATED);
        } else {
            return $this->json([
                'success' => false,
                'error' => $errors[0]->getMessage() . ' (' . $errors[0]->getPropertyPath(). ')'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
