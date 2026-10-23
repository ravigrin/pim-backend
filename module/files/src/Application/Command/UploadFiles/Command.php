<?php

declare(strict_types=1);

namespace Files\Application\Command\UploadFiles;

use Shared\Domain\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class Command implements CommandInterface
{
    /**
     * @param ImageDto[] $images
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $productId,
        #[Assert\Valid]
        public array  $images,
    ) {
    }
}
