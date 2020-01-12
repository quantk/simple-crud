<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Resolver;


use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ResolverError extends \Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    private ConstraintViolationListInterface $errors;

    /**
     * ResolverError constructor.
     * @param ConstraintViolationListInterface $errors
     */
    public function __construct(ConstraintViolationListInterface $errors)
    {
        parent::__construct("Resolver error");
        $this->errors = $errors;
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->errors as $error) {
            $result[] = [
                'path' => $error->getPropertyPath(),
                'message' => $error->getMessage()
            ];
        }

        return $result;
    }
}