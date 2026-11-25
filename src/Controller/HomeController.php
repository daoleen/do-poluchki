<?php

declare(strict_types=1);

namespace App\Controller;


use App\Annotation\ROLE_ADMIN;
use App\Annotation\ROLE_USER;
use App\Security\UserPrincipal;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


#[Route("/home")]
class HomeController extends AbstractController {

    public function __construct(private readonly LoggerInterface $logger) {
    }


    #[ROLE_ADMIN]
    #[Route(methods: ['GET'])]
    public function index(#[CurrentUser] UserPrincipal $principal): JsonResponse {
        $this->logger->info("Home page executed.");
        $this->logger->warning("Warn test message.");
        $this->logger->error("Error test message.");
        $this->logger->debug("Debug test message.");

        return $this->json([
            'status' => 'good',
            'principal' => $principal,
        ]);
    }

}
