<?php

namespace App\Controller;

use App\DTO\Request\LoginRequestDTO;
use App\Mappers\PostRequestMapper;
use App\Security\Authorization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

class AuthController extends AbstractController
{
    private readonly Authorization $authorization;

    #[Required]
    public function setDependencies(
        Authorization $authorization,
    ): void {
        $this->authorization = $authorization;
    }

    #[Route(path: '/auth/login', name: 'app_auth', methods: ['POST'])]
    public function index(
        #[PostRequestMapper] LoginRequestDTO $dto,
    ): JsonResponse {
        return $this->json($this->authorization->login($dto->login, $dto->password));
    }
}
