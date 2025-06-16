<?php

namespace App\Controller;

use App\Entity\Empresa;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmpresaController extends AbstractController
{
    /**
     * @Route("/empresa", name="empresa_index")
     */
    public function index(): Response
    {
        // Lógica para listar empresas
        return $this->render('../templates/index.html.twig'); 
}