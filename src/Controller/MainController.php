<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\AddProductType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerAwareTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class MainController extends AbstractController
{
    use LoggerAwareTrait;

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $offset = $request->get('offset');
            $limit = $request->get('limit');

            $products = $this
                ->getDoctrine()
                ->getRepository(Product::class)
                ->findFirstNProducts(3, 1291);
        } else {
            $products = $this
                ->getDoctrine()
                ->getRepository(Product::class)
                ->findFirstNProducts(3, 1);
        }

        return $this->render('base.html.twig', [
            'products' => $products
        ]);
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

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse([
                "status" => "success",
                "message" => null
            ]);

        } catch(ORMException $e) {
            $this->logger->error("Could not add user with whatsapp:".$user->getTelephone());
            return new JsonResponse([
                "status" => "failed",
                "message" => json_encode($e)
            ]);
        }
    }

    /**
     * @Route("/products", name="get_products")
     * @Method("GET")
     */
    public function getProducts(Request $request, ProductRepository $repo)
    {
        $products = $repo->findAll();

        dump($products);

        return $this->render('products/listProducts.html.twig', [
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
    public function checkCheersLinks()
    {
        $ret = [];
        do {
            $product = $this->getDoctrine()->getRepository(Product::class)->findOneEmptyLink();
            $this->generateCheersLinks($product);
            array_push($ret, $product->getShortTitle());
        } while($product);

        return new Response("Html generated for products: ".json_encode($ret));
    }

    private function generateCheersLinks($product) {
        if(!$product) {
            return new Response("No more HTML files to generate");
        }

        $name = substr($product->getAffiliateLink(), strpos($product->getAffiliateLink(), ".to/")+4, strlen($product->getAffiliateLink()));
        $product->setCheersLink("http://cheersbrosnan.com/p/$name");
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $content = $this->renderView('products/productLinkTemplate.html.twig', [
            'shortTitle' => $product->getShortTitle(),
            'description' => $product->getDescription(),
            'affiliateLink' => $product->getAffiliateLink(),
            'imageLink' => $product->getImage()
        ]);

        file_put_contents(__DIR__."/../../public/p/$name.html", $content);
    }
}
