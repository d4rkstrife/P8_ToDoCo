<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct(private TaskRepository $taskRepo){

    }

    #[Route('/', name: 'homepage')]
    public function indexAction(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
