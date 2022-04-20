<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product', name: 'product-')]
class ProductController extends AbstractController
{
    #[Route('/all', name:'all')]
    public function all(ProductRepository $productRepository)
    {

        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', ['products' => $products]);
    }

    #[Route('/add_new', name:'add-new')]
    public function add_new_product(Request $request, ProductRepository $productRepository) 
    {

        
        $product = new Product();
        
        if(isset($_POST['add_new_btn'])){
            $product->setName($request->request->get('product_name'));
            $product->setPrice($request->request->get('product_price'));
            $product->setSlug($request->request->get('product_slug'));
            $productRepository->add($product);
            return $this->redirectToRoute('product-all');
        }
        
        return $this->renderForm("product/add_new.html.twig", ['request' => $request]);
    }

    #[Route('/edit_product/{id}', name: 'edit-product')]
    public function edit_product($id, ProductRepository $productRepository, Request $request)
    {
        $product = $productRepository->find($id);

        if($product == null)
                throw $this->createNotFoundException('Product not found.');

        if(isset($_POST['edit-btn'])){
            $product->setName($request->request->get('product_name'));
            $product->setPrice($request->request->get('product_price'));
            $product->setSlug($request->request->get('product_slug'));
            $productRepository->add($product);
        return $this->redirectToRoute('product-all');
        }

        return $this->renderForm("product/edit.html.twig", ['product' => $product]);


    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete_product($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);

        if($product == null)
            throw $this->createNotFoundException('Product not found.');

        $productRepository->remove($product);

        return $this->redirectToRoute('product-all');

    }
}
