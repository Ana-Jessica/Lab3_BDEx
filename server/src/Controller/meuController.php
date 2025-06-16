<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeuController extends AbstractController
{
    /**
     * @Route("/minha-rota", name="minha_rota")
     */
    public function minhaAction(): Response
    {
        return $this->render('minha_template.html.twig', [
            // variáveis para o template
        ]);
    }
}