<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Deal;
use App\Form\DealFormType;
use App\Repository\DealRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DealController extends AbstractController
{
    private DealRepository $dealRepository;
    private ManagerRegistry $doctrine;
    public function __construct(DealRepository $dealRepository, ManagerRegistry $doctrine)
    {
        $this->dealRepository = $dealRepository;
        $this->doctrine = $doctrine;
    }

    // #[Route('/', name: 'deal_list', methods: ['GET'])]
    #[Route('/deal/list', name: 'deal_list', methods: ['GET'])]
    public function index(): Response
    {
        $em = $this->doctrine->getManager();
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('index.html.twig', ['categories' => $categories]);
    }

    #[Route('/deal/show/{dealId}', name: 'deal_show', requirements: ['dealId'=>'\d+'], methods: ['GET'])]
    public function show($dealId)
    {
        $deal = $this->dealRepository->find($dealId);
        dd($deal);
        return new Response("<html lang='fr'><body><h1>Le dealId est $dealId </h1></body></html>");
    }

    #[Route('/deal/create', name: 'deal_create')]
    public function create(Request $request): Response
    {
        $deal = new Deal();
        $form = $this->createForm(DealFormType::class, $deal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $deal = $form->getData();
            $em = $this->doctrine->getManager();
            $em->persist($deal);
            $em->flush();
            $this->addFlash('success', 'Deal enregistré!');
            return $this->redirectToRoute('deal_list');
        }
        return $this->render('create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/deal/toggle/{dealId}', name: 'deal_toggle', requirements: ['dealId'=>'\d+'], methods: ['GET'])]
    public function toggleEnableAction($dealId): Response
    {
        $em = $this->doctrine->getManager();
        $deal = $this->doctrine->getRepository(Deal::class)->find($dealId);
        if (!$deal) {
            throw $this->createNotFoundException('Le deal n\'existe pas.');
        }
        $deal->setEnable(!$deal->isEnable());
        $em->flush();
        dd($deal);
        return new Response("<html lang='fr'><body><h1>Enable est passé à $de </h1></body></html>");
    }
}