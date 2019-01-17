<?php

namespace App\Controller;

use App\Entity\ParseQueue;
use App\Repository\ParseQueueRepository;
use App\Utils\EbayParser;
use App\Utils\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ParseController extends AbstractController
{
    /**
     * @Route("/getProductInfo", name="parse")
     */
    public function amazonParse(Parser $parser)
    {
        $status = [];
        //@TODO parse as long as there is links available
//        for($i = 0; $i < 1; $i++) {
            $ret = $parser->getProductInfo();
            dump($ret);
//        }
        return new JsonResponse($status);
    }
}
