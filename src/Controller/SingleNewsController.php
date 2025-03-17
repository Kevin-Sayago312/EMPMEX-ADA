<?php

namespace App\Controller;

use App\Entity\Comentario;
use App\Repository\PedidoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SingleNewsController extends AbstractController
{
    #[Route('/single_news', name: 'app_single_news')]
    public function index(Request $request, EntityManagerInterface $entityManager, PedidoRepository $pedidoRepository): Response
    {
        return $this->render('single_news/single_news.html.twig', [
            'controller_name' => 'SingleNewsController',
        ]);
    }

    #[Route('/comentario/nuevo', name: 'nuevo_comentario', methods: ['POST'])]
    public function nuevoComentario(Request $request, EntityManagerInterface $entityManager, PedidoRepository $pedidoRepository): Response
    {
        // Obtener el usuario actualmente autenticado
        $usuario = $this->getUser();
        
        // Verificar si el usuario tiene al menos un pedido
        $pedidos = $pedidoRepository->findBy(['user' => $usuario]);
        
        if (count($pedidos) === 0) {
            // Si no tiene pedidos, devolver un error en formato JSON
            return new JsonResponse(['error' => 'Necesitas tener al menos un pedido para dejar un comentario.'], Response::HTTP_FORBIDDEN);
        }

        // Crear el nuevo comentario
        $comentario = new Comentario();
        $comentario->setUser($usuario);
        $comentario->setFecha(new \DateTime());
        $comentario->setContenido($request->request->get('comment'));

        $entityManager->persist($comentario);
        $entityManager->flush();

        // Responder con éxito en formato JSON
        return new JsonResponse(['success' => 'Comentario enviado con éxito.'], Response::HTTP_CREATED);
    }
}
