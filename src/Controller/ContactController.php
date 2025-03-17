<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            // Captura los datos del formulario
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $phone = $request->request->get('phone');
            $subject = $request->request->get('subject');
            $message = $request->request->get('message');

            // Construye el email
            $emailMessage = (new Email())
                ->from('no-reply@tu-dominio.com') // Usar una dirección de remitente válida
                ->to('hansellsayago@hotmail.com') // Tu correo
                ->subject($subject)
                ->text("Nombre: $name\nCorreo: $email\nTeléfono: $phone\nMensaje: $message");

            try {
                // Envía el email
                $mailer->send($emailMessage);

                // Redirige o muestra un mensaje de éxito
                $this->addFlash('success', 'Tu mensaje ha sido enviado con éxito.');
            } catch (\Exception $e) {
                // Maneja los errores de envío de correo
                $this->addFlash('error', 'Hubo un problema al enviar tu mensaje: ' . $e->getMessage());
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
