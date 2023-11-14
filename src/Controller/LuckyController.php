<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LuckyController extends AbstractController
{
    #[Route('/lucky', name: 'app_lucky')]
    public function index(): Response
    {
        return $this->render('lucky/index.html.twig', [
            'controller_name' => 'LuckyController',
        ]);
    }

    #[Route('/lucky/number', name: 'app_lucky_number')]
    public function show_number(): Response
    {
        $number = random_int(0, 100);
        return new Response('Lucky number: '.$number);
    }

    #[Route('/lucky/number_for_username', name: 'show_number_v2')]
    public function show_number_v2(Request $request): Response
    {
        $name = $request->query->get('name', 'Deniau ');

        $number = random_int(0, 100);

        return new Response('Nombre tiré au sort :  '.$number.' pour '.$name);

    }

    #[Route('/lucky/number_v3', name: 'show_number_v3')]
    public function show_number_v3(Request $request): Response
    {
        $number = random_int(0, 100);

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);

    }

}


