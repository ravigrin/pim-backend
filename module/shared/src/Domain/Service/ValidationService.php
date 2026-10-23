<?php

namespace Shared\Domain\Service;

use Shared\Domain\Event\EventInterface;
use Shared\Domain\Command\CommandInterface;
use Shared\Domain\Query\QueryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class ValidationService
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @return array<string, string>|null
     */
    public function validate(CommandInterface|QueryInterface|EventInterface $message): ?array
    {
        $errors = $this->validator->validate($message);
        $result = null;
        if ($errors->count() > 0) {
            $result = [];
            for ($i = 0; $i < $errors->count(); $i++) {
                $error = $errors->get($i);
                $result[$error->getPropertyPath()] = (string)$error->getMessage();
            }
        }
        return $result;
    }

}
