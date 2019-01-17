<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\AddProductType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/regWhatsapp", name="register_whatsapp")
     * @Method("POST")
     */
    public function register(Request $request)
    {
        $params = $request->request->all();
        dump($params);
            $user = new User();
            $user->setTelephone($params['number']);
            $user->setEmail('');
            $user->setName('');
            $user->setSignupDate(new \DateTime(date('Y-m-d h:i:s'), new \DateTimeZone('Europe/London')));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


//            $this->addFlash("success", "Hey Bro, welcome!");
//            return $this->redirectToRoute('main');
//        }
        return new JsonResponse($params);
//        return $this->render('products/index.html.twig');
    }

    /**
     * @Route("/products", name="get_products")
     * @Method("GET")
     */
    public function getProducts(Request $request, ProductRepository $repo)
    {
        $products = $repo->findAll();

        dump($products);

        return $this->render('products/showProducts.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/addProduct", name="add_product")
     * @Method("POST")
     */
    public function addProducts(Request $request, ProductRepository $repo)
    {
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this->addFlash("success", "Product added");
            return $this->redirectToRoute('get_products');
        }

        return $this->render('products/addProduct.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/generateHtmlFiles", name="generate_html")
     * @Method("GET")
     */
    public function generateHtml()
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find(33);

        if(!$product) {
            return new Response("No more HTML files to generate");
        }

        $content = $this->renderView('products/template.html.twig', [
            'shortTitle' => $product->getShortTitle(),
            'description' => $product->getDescription(),
            'affiliateLink' => $product->getAffiliateLink(),
            'imageLink' => $product->getImage()
        ]);

        file_put_contents(__DIR__."/../../public/p/xyz.html", $content);

        return new Response("Html generated for product: ".$product->getShortTitle());
    }
}
