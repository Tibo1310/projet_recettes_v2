<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Taxe\CalculatorTaxe;

class HelloController extends AbstractController
{
    private $taxeCalculator;

    public function __construct(CalculatorTaxe $taxeCalculator) {
        $this->taxeCalculator = $taxeCalculator;
    }

    #[Route('/hello', name: 'app_hello')]
    public function index(): Response
    {
        $prixHT = 360;
        $tva = $this->taxeCalculator->calculerTVA($prixHT);
        $ttc = $this->taxeCalculator->calculerTTC($prixHT);

        return $this->render('hello/index.html.twig', [
            'controller_name' => "HelloController",
            'message' => "Pour un prix de {$prixHT} EUR HT, le service a calculé que la TVA était de {$tva} EUR et le TTC de {$ttc} EUR."
        ]);
    }

    #[Route('/show_hello_world', name: 'show_hello_world')]
    public function show_hello_world(): Response
    {
        return $this->render('hello/hello_world.html.twig', []);
    }
}
