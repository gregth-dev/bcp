<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class HomeController extends AbstractController
{
    /**
     * @Route("/bcp", name="dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('bcp/dashboard.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
