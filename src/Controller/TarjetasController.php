<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TarjetasController extends AbstractController
{
    #[Route('/tarjetas', name: 'app_tarjetas')]
    public function index(): Response
    {
        return $this->render('tarjetas/tarjetas.html.twig', [
            'controller_name' => 'TarjetasController',
        ]);
    }
}
