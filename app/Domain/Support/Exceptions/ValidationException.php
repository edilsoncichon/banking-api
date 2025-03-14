<?php declare(strict_types=1);

namespace App\Domain\Support\Exceptions;

final class ValidationException extends DomainException
{
    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Os dados informados estão incorretos.', 422);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
