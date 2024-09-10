<?php

namespace App\Services;

use App\DTO\Request\Paste\PostPasteDTO;
use App\Entity\Paste;
use App\Entity\User;
use App\Repository\Exceptions\PasteNotFoundException;
use App\Repository\PasteRepository;
use App\Services\Exceptions\Paste\UserUnauthorizedException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\ByteString;

class PasteService
{
    private ?User $authUser;

    public function __construct(
        private readonly PasteRepository $pasteRepository,
        private readonly Security $security,
    ) {
        /** @var ?User $user */
        $user = $this->security->getUser();
        $this->authUser = $user;
    }

    /**
     * @throws UserUnauthorizedException
     */
    public function create(PostPasteDTO $dto): array
    {
        if ('private' === $dto->availability && null === $this->authUser) {
            throw new UserUnauthorizedException();
        }

        $expiresAt = match ($dto->expirationTime) {
            '10M', '1H', '3H' => (new \DateTimeImmutable())->add(new \DateInterval("PT{$dto->expirationTime}")),
            '1D', '1W', '1M' => (new \DateTimeImmutable())->add(new \DateInterval("P{$dto->expirationTime}")),
            default => null,
        };

        $paste = new Paste();
        $paste
            ->setId(ByteString::fromRandom(10)->toString())
            ->setName($dto->name)
            ->setText($dto->text)
            ->setCreatedBy($this->authUser)
            ->setExpirationTime($expiresAt)
            ->setAvailability($dto->availability);
        $this->pasteRepository->saveEntity($paste);

        return [
            'id' => $paste->getId(),
            'name' => $paste->getName(),
            'text' => $paste->getText(),
            'createdBy' => $paste->getCreatedBy()->getLogin(),
            'expTime' => $paste->getExpirationTime()->format('Y-m-d H:i:s'),
            'availability' => $paste->getAvailability(),
        ];
    }

    public function getList(): array
    {
        return array_map(
            fn ($paste) => [
                'id' => $paste->getId(),
                'name' => $paste->getName(),
                'text' => $paste->getText(),
                'createdBy' => $paste->getCreatedBy()->getLogin(),
                'expTime' => $paste->getExpirationTime()->format('Y-m-d H:i:s'),
            ],
            $this->pasteRepository->getPublicPasteList()
        );
    }

    /**
     * @throws PasteNotFoundException
     */
    public function getPaste(string $id): array
    {
        $paste = $this->pasteRepository->getPasteById($id, $this->authUser);

        return [
            'id' => $paste->getId(),
            'name' => $paste->getName(),
            'text' => $paste->getText(),
            'createdBy' => $paste->getCreatedBy()->getLogin(),
            'expTime' => $paste->getExpirationTime()->format('Y-m-d H:i:s'),
            'availability' => $paste->getAvailability(),
        ];
    }
}
