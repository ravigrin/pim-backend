<?php

namespace Files\Domain\ValueObject;

class ImageDto
{
    public function __construct(
        public string $content,
        public string $type,
        public string $size
    ) {
    }

    public function __toString(): string
    {
        return serialize([
            'content' => $this->content,
            'type' => $this->type,
            'size' => $this->size
        ]);
    }

    /**
     * @throws \Exception
     */
    public static function fromString(string $serializeString): ImageDto
    {
        $content = unserialize($serializeString);
        if (isset($content['content']) && isset($content['type']) && isset($content['size'])) {
            return new self(
                content: $content['content'],
                type: $content['type'],
                size: $content['size']
            );
        }
        throw new \Exception('serializeString not valid');
    }
}
