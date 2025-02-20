<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GifController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }
    #[Route('/gifs', name: 'gif_index', methods: ['GET'])]
    public function index(Request $request)
    {
        $name = $request->query->get('name');
        $gifs = [];
        if ($name) {
            $response = $this->client->request(
                'GET',
                "https://g.tenor.com/v1/search?q=$name&key=LIVDSRZULELA&limit=8"
            );
            $page = $response->toArray();
            $items = $page['results'];
            foreach ($items as $item) {
                $gifs[] = $item['media'][0]['gif']['url'];
            }
        }
        return $this->render('gifs.html.twig', ['gifs' => $gifs]);
    }
}