<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/hello/{name}', name:'app_hello')]
    public function index($name)
    {
        return new Response("<html lang='fr'><body><h1>Salut $name</h1></body></html>");
    }

}