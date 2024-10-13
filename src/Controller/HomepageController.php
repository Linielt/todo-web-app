<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            return new RedirectResponse('/todos');
        }
        return $this->render('homepage/index.html.twig');
    }
}
