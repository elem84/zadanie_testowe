<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BHPController extends AbstractController
{
    #[Route('/bhp/index', name: 'bhp_index')]
    public function index(): Response
    {
        return $this->render('bhp/index.html.twig');
    }

    #[Route('/bhp/add', name: 'bhp_add')]
    public function add(Request $request): Response
    {
        return $this->render('bhp/add.html.twig');
    }

    #[Route('/bhp/edit/{bhp}', name: 'bhp_edit')]
    public function edit(Request $request, $bhp): Response
    {
        return $this->render('bhp/edit.html.twig');
    }
}
