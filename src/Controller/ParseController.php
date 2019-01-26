<?php

namespace App\Controller;

use App\Entity\ParseQueue;
use App\Repository\ParseQueueRepository;
use App\Utils\DanielParser;
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
        array_push($status, $parser->getProductInfo());
        return new JsonResponse($status);
    }

    /**
     * @Route("/daniel", name="parse")
     */
    public function danielParse(DanielParser $parser)
    {
        $status = [];
        array_push($status, $parser->getProductInfo());
        return new JsonResponse($status);
    }
}
