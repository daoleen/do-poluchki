<?php

declare(strict_types=1);

namespace App\Controller;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


#[Route("/home")]
class HomeController extends AbstractController {

    public function __construct(private readonly LoggerInterface $logger) {
    }


    #[Route(methods: ['GET'])]
    public function index(): JsonResponse {
        $this->logger->info("Home page executed.");
        $this->logger->warning("Warn test message.");
        $this->logger->error("Error test message.");
        $this->logger->debug("Debug test message.");

        return $this->json([
            'status' => 'good'
        ]);
    }

}
