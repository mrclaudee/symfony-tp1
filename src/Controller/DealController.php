<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DealController extends AbstractController
{
    // #[Route('/', name: 'deal_list', methods: ['GET'])]
    #[Route('/deal/list', name: 'deal_list_2', methods: ['GET'])]
    public function index(): Response
    {
        return new Response("<html lang='fr'><body><h1>La list</h1></body></html>");
    }

    #[Route('/deal/show/{dealId}', name: 'deal_show', requirements: ['dealId'=>'\d+'], methods: ['GET'])]
    public function show($dealId)
    {
        return new Response("<html lang='fr'><body><h1>Le deal est $dealId</h1></body></html>");
    }
}