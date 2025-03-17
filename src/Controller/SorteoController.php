<?php

namespace App\Controller;

use App\Entity\Sorteo;
use App\Repository\CategoriasRepository;
use App\Repository\ProductosRepository;
use App\Repository\SorteoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SorteoController extends AbstractController
{
    private $userRepository;
    private $productosRepository;
    private $categoriasRepository;
    private $sorteoRepository;

    public function __construct(UserRepository $userRepository, ProductosRepository $productosRepository, CategoriasRepository $categoriasRepository, SorteoRepository $sorteoRepository)
    {
        $this->userRepository = $userRepository;
        $this->productosRepository = $productosRepository;
        $this->categoriasRepository = $categoriasRepository;
        $this->sorteoRepository = $sorteoRepository;
    }


    #[Route('/sorteo', name: 'app_sorteo')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        $productos = $this->productosRepository->findAll();
        $categorias = $this->categoriasRepository->findAll();
        $sorteos = $this->sorteoRepository->findAll();
        return $this->render('sorteo/index.html.twig', [
            'users' => $users,
            'productos' => $productos,
            'categorias' => $categorias,
            'sorteos' => $sorteos,
        ]);
    }

    #[Route('/eliminar_sorteo/{id}', name: 'app_sorteo_delete', methods: ['POST'])]
public function deleteSorteo(Request $request, Sorteo $sorteo, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$sorteo->getId(), $request->request->get('_token'))) {
        $entityManager->remove($sorteo);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_sorteo', [], Response::HTTP_SEE_OTHER);
}

#[Route('/editar_sorteo/{id}', name: 'app_sorteo_edit', methods: ['POST'])]
public function editarSorteo(Request $request, EntityManagerInterface $entityManager, int $id): Response
{
    // Obtener el nuevo nombre del sorteo del formulario
    $nuevoNombre = $request->request->get('nombre');

    /** @var UploadedFile $photo */
    $photo = $request->files->get('photo');

    // Buscar el sorteo existente por su ID
    $sorteo = $entityManager->getRepository(Sorteo::class)->find($id);

    // Si el sorteo no existe, mostrar un mensaje de error y redireccionar
    if (!$sorteo) {
        $this->addFlash('error', 'Sorteo no encontrado.');
        return $this->redirectToRoute('app_sorteo');
    }

    // Establecer el nuevo nombre del sorteo
    $sorteo->setNombre($nuevoNombre);

    if ($photo) {
        $newFilename = uniqid() . '.' . $photo->guessExtension();

        try {
            $photo->move(
                $this->getParameter('uploads_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            $this->addFlash('error', 'Error al subir la imagen.');
            return $this->redirectToRoute('app_sorteo');
        }

        $sorteo->setPhoto($newFilename);
    }

    // Persistir los cambios en la base de datos
    $entityManager->flush();

    // Redireccionar a donde sea necesario después de editar el sorteo
    return $this->redirectToRoute('app_sorteo');
}

}
