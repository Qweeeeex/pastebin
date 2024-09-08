<?php

namespace App\DTO\Request\Paste;

use App\DTO\Request\RequestDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PostPasteDTO implements RequestDTOInterface
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $text;

    #[Assert\Choice(['10M', '1H', '3H', '1D', '1W', '1M'])]
    public ?string $expirationTime = null;

    #[Assert\Choice([
        'public',
        'unlisted',
        'private',
    ])]
    public ?string $availability = null;
}