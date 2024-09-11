<?php

namespace App\Controller;

use App\DTO\Request\GetPasteListDTO;
use App\DTO\Request\PostUserDTO;
use App\Mappers\GetRequestMapper;
use App\Mappers\PostRequestMapper;
use App\Services\Exceptions\Paste\UserUnauthorizedException;
use App\Services\PasteService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

class UserController extends AbstractController
{
    private readonly UserService $userService;
    private readonly PasteService $pasteService;

    #[Required]
    public function setDependencies(
        UserService $userService,
        PasteService $pasteService,
    ): void {
        $this->userService = $userService;
        $this->pasteService = $pasteService;
    }

    #[Route(
        path: '/users/register',
        name: 'createUser',
        methods: [Request::METHOD_POST]
    )]
    public function createUser(
        #[PostRequestMapper] PostUserDTO $dto,
    ): JsonResponse {
        return $this->json($this->userService->createUser($dto), Response::HTTP_CREATED);
    }

    /**
     * @throws UserUnauthorizedException
     */
    #[Route(
        path: '/users/pastes',
        name: 'users_private_pastes',
        methods: [Request::METHOD_GET]
    )]
    public function getPastes(
        #[GetRequestMapper] GetPasteListDTO $dto,
    ): JsonResponse {
        if (null === $this->getUser()) {
            throw new UserUnauthorizedException();
        }

        return $this->json($this->pasteService->getPrivatePasteList($dto));
    }
}
