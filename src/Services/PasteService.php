<?php

namespace App\Services;

use App\DTO\Request\Paste\PostPasteDTO;
use App\Entity\Paste;
use App\Repository\PasteRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\SecurityBundle\Security;

class PasteService
{
    public function __construct(
        private readonly PasteRepository $pasteRepository,
        private readonly Security $security,
    ) {
    }

    public function create(PostPasteDTO $dto): Paste
    {
        $expiresAt = match ($dto->expirationTime) {
            '10M', '1H', '3H' => (new DateTimeImmutable())->add(new DateInterval("PT{$dto->expirationTime}")),
            '1D', '1W', '1M' => (new DateTimeImmutable())->add(new DateInterval("P{$dto->expirationTime}")),
            default => null
        };
        $paste = new Paste();
        $paste->setName($dto->name)
            ->setText($dto->text)
            ->setCreatedBy($this->security->getUser())
            ->setExpirationTime($expiresAt)
            ->setAvailability($dto->availability);
        $this->pasteRepository->saveEntity($paste);

        return $paste;
    }

    public function getList(): array
    {
        return $this->pasteRepository->findBy(['availability' => 'public']);
    }
}