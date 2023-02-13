<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoginController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
        private string $backendUrl
    )
    {}

    #[Route('/login', name: 'app_login')]
    public function index(Request $request): Response
    {
        $response = $this->client->request(
            'GET',
            $this->backendUrl . '/api/login_check',
            [
                'email' => $request->request->get('email'),
                'password' => $request->request->get('password')
            ],
        );

        $content = $response->toArray();

        if (isset($content['token'])) {
            return $this->render('display/index.html.twig', [
                'token' => $content['token'],
            ]);
        }

        return $this->render('login/index.html.twig', [
            'state' => 'not logged in',
        ]);
    }
}
