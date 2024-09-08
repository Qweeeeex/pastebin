<?php

namespace App\Controller;

use App\DTO\Request\Paste\PostPasteDTO;
use App\Mappers\PostRequestMapper;
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
}
