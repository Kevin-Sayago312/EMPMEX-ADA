<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HistoriaController extends AbstractController
{
    #[Route('/historia', name: 'app_historia')]
    public function index(): Response
    {
        return $this->render('historia/historia.html.twig', [
            'controller_name' => 'HistoriaController',
        ]);
    }
}
