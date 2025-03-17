<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminComprasController extends AbstractController
{
    #[Route('/admin_compras', name: 'app_admin_compras')]
    public function index(): Response
    {
        return $this->render('admin_compras/admin_compras.html.twig', [
            'controller_name' => 'AdminComprasController',
        ]);
    }
}
