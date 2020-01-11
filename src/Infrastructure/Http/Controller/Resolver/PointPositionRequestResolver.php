<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Controller\Resolver;


use App\Domain\Segment\Point;
use App\Infrastructure\Http\Request\PointPositionRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PointPositionRequestResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * PointPositionRequestResolver constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return PointPositionRequest::class === $argument->getType();
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator|iterable
     * @throws ResolverError
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $x1 = $request->query->get('x1');
        $y1 = $request->query->get('y1');

        $pointConstraint = [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'float',
                'message' => 'Value should be float type'
            ])
        ];
        $violations = $this->validator->validate(
            new Assert\Collection([
                'x1' => $pointConstraint,
                'y1' => $pointConstraint
            ])
        );

        if (count($violations) > 0) {
            throw new ResolverError($violations);
        }

        yield new PointPositionRequest(Point::create($x1, $y1));
    }
}