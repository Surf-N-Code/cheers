<?php

namespace App\Utils;

use App\Entity\DanielProducts;
use Doctrine\Common\Persistence\ObjectManager;
use PHPHtmlParser\Dom;
use Psr\Log\LoggerAwareTrait;

class DanielParser{

    use LoggerAwareTrait;

    private $dom;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ObjectManager $em, \Twig_Environment $templating) {
        $this->dom = new Dom;
        $this->em = $em;
        $this->templating = $templating;
    }

    public function getProductInfo() {
       $ret = [];

       //474 - 478
       for($i = 689; $i <= 2000; $i++) {
           $res = $this->parseLink('https://www.crowdfox.com/computer-zubeh-r-1615?page='.$i, $i);
           array_push($ret, $res, $i);
//           $this->doSleep(1,1);
       }
       return $ret;
    }

    private function parseLink($link, $page) {
        $ret = [];
        $this->dom->loadFromUrl($link);
        dump($link);
        $blocks = $this->dom->find('.catalog-listing-product');
//        dump($blocks);
        if(count($blocks) > 0) {
            foreach($blocks as $block) {
                $p = new DanielProducts();

                $title = html_entity_decode($block->find('h3')->text());
                $text = html_entity_decode($block->find('.catalog-listing-product-description')->text());
                $offers = $block->find('.offers');
                $offer = null;
                if(count($offers) > 0) {
                    $find = '';
                    preg_match('/[0-9]+/', $offers->text(), $find);
                    $offer = $find[0];
                }
                $pRange = $block->find('.price-range')->innerHtml();
                $finds = '';
                preg_match_all('/[0-9\,]+/', $pRange, $finds);
                dump($title);
                dump($text);
                dump($offers);
                dump($finds);
                $p->setOffers($offer);
                $p->setPriceFrom((isset($finds[0][0]) ? str_replace(',', '.', $finds[0][0]) : 0));
                $p->setPriceTo((isset($finds[0][1]) ? str_replace(',', '.', $finds[0][1]) : 0));
                $p->setText($text);
                $p->setTitle($title);
                $p->setPage($page);
                $this->em->persist($p);
                $this->em->flush();

                array_push($ret, $p);
            }
        }
        return $ret;
    }

    public function doSleep($min, $max)
    {
        $rand = rand($min, $max);
//        dump("sleeping for: ".$rand);
        sleep(0);
    }

    private function now() {
        $d = new \DateTime(date('Y-m-d h:i:s'));
        return $d;
    }
}
