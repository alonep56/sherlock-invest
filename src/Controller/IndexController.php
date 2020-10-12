<?php

namespace App\Controller;

use App\Service\CoinGecko;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $history = new CoinGecko();
        dump($history);


        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'history' => $history->calculFormatage()
        ]);
    }
}
