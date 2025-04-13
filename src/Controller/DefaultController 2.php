<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return new Response('<h1>✅ API Pulse bien démarrée</h1>');
    }
}
