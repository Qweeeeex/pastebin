<?php

namespace App\Controller;

use App\DTO\Request\IdRequestDTO;
use App\DTO\Request\Paste\PostPasteDTO;
use App\Entity\Paste;
use App\Mappers\GetRequestMapper;
use App\Mappers\PostRequestMapper;
use App\Repository\Exceptions\PasteNotFoundException;
use App\Services\Exceptions\Paste\UserUnauthorizedException;
use App\Services\PasteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

class PasteController extends AbstractController
{
    private PasteService $pasteService;

    #[Required]
    public function setDependencies(
        PasteService $pasteService,
    ): void {
        $this->pasteService = $pasteService;
    }

    /**
     * @throws UserUnauthorizedException
     */
    #[Route(
        path: '/pastes',
        name: 'app_paste',
        methods: [Request::METHOD_POST]
    )]
    public function createPaste(
        #[PostRequestMapper] PostPasteDTO $dto
    ): JsonResponse {
        return $this->json($this->pasteService->create($dto));
    }

    #[Route(
        path: '/pastes',
        name: 'get_paste_list',
        methods: [Request::METHOD_GET]
    )]
    public function getPasteList(): JsonResponse
    {
        return $this->json($this->pasteService->getList());
    }

    /**
     * @throws PasteNotFoundException
     */
    #[Route(
        path: '/pastes/{id}',
        name: 'get_paste',
        methods: [Request::METHOD_GET]
    )]
    public function getPaste(
        #[GetRequestMapper] IdRequestDTO $dto
    ): JsonResponse
    {
        return $this->json($this->pasteService->getPaste($dto->id));
    }
}
