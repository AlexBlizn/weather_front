<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DisplayController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
        private string $backendUrl
    )
    {}

    #[Route('/display', name: 'app_display')]
    public function index(Request $request): Response
    {
        $token = $request->request->get('token');

        if (empty($token)) {
            return $this->render('login/index.html.twig', []);
        }

        $response = $this->client->request(
            'GET',
            $this->backendUrl . sprintf(
                '/api/weather/%s/%s',
                $request->request->get('city'),
                $request->request->get('type')
            ),
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]
        );

        $content = $response->toArray();

        return $this->render('display/index.html.twig', [
            'token' => $token,
            'result' => $content
        ]);
    }
}
