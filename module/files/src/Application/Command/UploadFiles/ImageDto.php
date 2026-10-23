<?php

namespace Files\Application\Command\UploadFiles;

use Symfony\Component\Validator\Constraints as Assert;

class ImageDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $image,
        #[Assert\NotBlank]
        public string $type,
        #[Assert\NotBlank]
        public string $size,
    ) {
    }
}
