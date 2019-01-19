<?php

namespace App\Utils;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\ORMException;
use PHPHtmlParser\Dom;
use Psr\Log\LoggerAwareTrait;

class Parser {

    use LoggerAwareTrait;

    private $asin;
    private $dom;
    /**
     * @var ObjectManager
     */
    private $em;
    private $productUrl;
    private $isDebug = false;
    /**
     * @var \Twig_Environment
     */
    private $templating;

    public function __construct(ObjectManager $em, \Twig_Environment $templating) {
        $this->dom = new Dom;
        $this->em = $em;
        $this->templating = $templating;
    }

    public function getProductInfo() {
       $ret = [];
       do {
           $product = $this->em->getRepository(Product::class)->findOneEmptyImage();
           array_push($ret, $this->parseLink($product->getAmazonLink()));
           $this->doSleep(1,3);
       } while($product);
       return $ret;
    }

    private function parseLink($link) {
        $this->dom->loadFromUrl($link, ['enforceEncoding' => true], new CurlConnector());
        dump($link);
        $imageData = $this->dom->find('#landingImage');
        dump($imageData);
        if(count($imageData) > 0) {
            $title = $imageData->getAttribute('alt');

            if (stristr($imageData->getAttribute('src'), "http")){
                $image = $imageData->getAttribute('src');
            } else {
                $image = $imageData->getAttribute('data-old-hires');
            }

        } else {
            $title = 'not found - please enter manually';
            $image = 'not found - please enter manually';
        }

        $description = '';
        $descData = $this->dom->find('#feature-bullets');
        if(count($descData) > 0) {
            $lis = $descData->find('.a-list-item');

            if(count($lis) > 0) {
                foreach($lis as $li) {
                    $description .= str_replace(ltrim($li->text()), '"', '');
                }
            } else {
                $description = 'not found - please enter manually';
            }
        }

        $product = $this->em->getRepository(Product::class)->findOneBy([
            'amazonLink' => $link
        ]);
        $product->setTitle($title);
        $product->setCheersLink('');
        $product->setDescription($description);
        $product->setImage($image);
        dump($product);

        if(count($descData) > 0 && count($imageData) > 0) {
            $this->generateProductHtml($product);
        }

        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }

    private function generateProductHtml(Product $product) {
        $content = $this->templating->render('products/template.html.twig', [
            'shortTitle' => $product->getShortTitle(),
            'description' => $product->getDescription(),
            'affiliateLink' => $product->getAffiliateLink(),
            'imageLink' => $product->getImage()
        ]);

        $name = substr(strpos($product->getAffiliateLink(), "/")+1, strlen($product->getAffiliateLink()));

        $product->setCheersLink("http://www.cheersbrosnan.com/p/$name");
        $this->em->persist($product);
        $this->em->flush();

        file_put_contents(__DIR__."/../../public/p/$name.html", $content);
    }

    public function doSleep($min, $max)
    {
        $rand = rand($min, $max);
        dump("sleeping for: ".$rand);
        sleep(0);
    }

    private function now() {
        $d = new \DateTime(date('Y-m-d h:i:s'));
        return $d;
    }
}
