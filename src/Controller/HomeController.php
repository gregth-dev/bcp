<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    /**
     * @Route("/bcp", name="index")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $firstName = ucfirst($this->getUser()->getFirstName());
        $this->addFlash('success', "Bienvenue $firstName !");
        return $this->render('bcp/index.html.twig');
    }
}
