<?php

namespace App\Controller;

use App\DTO\Request\PostUserDTO;
use App\Mappers\PostRequestMapper;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

class UserController extends AbstractController
{
    private readonly UserService $userService;

    #[Required]
    public function setDependencies(
        UserService $userService,
    ): void {
        $this->userService = $userService;
    }

    #[Route(
        path: '/users/register',
        name: 'createUser',
        methods: ['POST']
    )]
    public function createUser(
        #[PostRequestMapper] PostUserDTO $dto,
    ): JsonResponse {
        return $this->json($this->userService->createUser($dto), Response::HTTP_CREATED);
    }
}
