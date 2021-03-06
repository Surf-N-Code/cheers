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
        $products = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->findFirstNProducts(1, 6);

        return $this->render('base.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/fetchNextProducts/{i}", name="fetch_more_products")
     */
    public function fetchNextProducts(Request $request, $i) {
        $limit = $request->get('limit');
        if(!isset($limit) || $limit = '') {
            $limit = 6;
        }

        $productStartId = 1291 + ($i * $limit);
        $products = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->findFirstNProducts($productStartId, $limit);

        $lastPage = false;


        $html = '<div class="col-12 text-center appear-animation animated fadeInUp appear-animation-visible" id="signUpBtns"
                        data-appear-animation="fadeInUp"
                        data-appear-animation-delay="1200"
                        style="animation-delay: 1200ms;">
                    <a class="btn btn-primary  btn-xl font-weight-semibold text-2 px-5 py-3 mb-4 box-shadow-2"
                        href="https://api.whatsapp.com/send?phone=4921146811188&text=Hey%20Bro!%20Sende%20diese%20Nachricht%20und%20speichere%20unsere%20Nummer%20in%20deinen%20Kontakten%2C%20um%20unseren%20WhatsApp%20Service%20zu%20abonnieren!">
                        Whatsapp Signup <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>';

        if(!$products) {
            return new JsonResponse([
                'html' => $html,
                'lastPage' => true
            ]);
        }

        $htmlContent = "<div class='masonry row' data-plugin-masonry data-plugin-options=\"{'itemSelector': '.masonry-item'}\">";
        foreach ($products as $index => $product) {
            $htmlContent .= $this->renderView('products/productRowTemplate.html.twig', [
                'product' => $product
            ]);
        }
        $htmlContent .= "</div>";

        if(count($products) < $limit) {
            $lastPage = true;
            $htmlContent .= $html;
        }

        return new JsonResponse([
            'html' => $htmlContent,
            'lastPage' => $lastPage
        ]);
    }

    /**
     * @Route("/regWhatsapp", name="register_whatsapp")
     * @Method("POST")
     */
    public function register(Request $request)
    {
        $params = $request->request->all();
//        dump($params);
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

//        dump($products);

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
            $data = $form->getData();

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
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        foreach ($products as $index => $product) {
            $this->generateCheersLinks($product);
            array_push($ret, $product->getShortTitle());
        }

        return new Response("Html generated for products: ".json_encode($ret));
    }

    private function generateCheersLinks($product) {
        if(!$product) {
            return new Response("No more HTML files to generate");
        }

        $name = substr($product->getAffiliateLink(), strpos($product->getAffiliateLink(), ".to/")+4, strlen($product->getAffiliateLink()));
        $product->setCheersLink("https://cheersbrosnan.com/p/$name.html");
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

    /**
     * @Route("/getCheersLinks", name="get_cheers_links")
     */
    public function getCheersLinks() {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        foreach ($products as $index => $product) {
            if (!$product) {
                return new Response("No more HTML files to generate");
            }

            $name = substr($product->getAffiliateLink(), strpos($product->getAffiliateLink(), ".to/") + 4, strlen($product->getAffiliateLink()));
            $product->setCheersLink("https://cheersbrosnan.com/p/$name.html");
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
        }
        return new Response("");
    }

    /**
     * @Route("/changeHeartCount", name="changeHeartCount");
     */
    public function changeHeartCount(Request $request) {
        $delta = $request->get('delta');
        $productId = $request->get('productId');

        $product = $this->getDoctrine()->getRepository(Product::class)->find($productId);
        $product->changeLikes($delta);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new JsonResponse([$product->getLikes()]);
    }

    /**
     * @Route("/datenschutz", name="datenschutz")
     */
    public function datenschutz() {
        return $this->render('datenschutz.html.twig');
    }

    /**
     * @Route("/impressum", name="impressum")
     */
    public function impressum() {
        return $this->render('impressum.html.twig');
    }

    /**
     * @Route("/test", name="impressum")
     */
    public function test() {
        return $this->render('fbtest.html.twig');
    }
}
