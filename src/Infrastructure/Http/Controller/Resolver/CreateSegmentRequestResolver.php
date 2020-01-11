<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Controller\Resolver;


use App\Domain\Segment\Point;
use App\Infrastructure\Http\Request\CreateSegmentRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateSegmentRequestResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * CreateSegmentRequestResolver constructor.
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
        return CreateSegmentRequest::class === $argument->getType();
    }

    private function constraint()
    {
        $pointConstraint = [
            'x' => [
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Type([
                    'type' => 'float',
                    'message' => 'Value should be float type'
                ])
            ],
            'y' => [
                new Assert\NotNull(),
                new Assert\NotBlank(),
                new Assert\Type([
                    'type' => 'float',
                    'message' => 'Value should be float type'
                ])
            ],
        ];
        return new Assert\Collection([
            'left_side' => new Assert\Collection($pointConstraint),
            'right_side' => new Assert\Collection($pointConstraint),
        ]);
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator|iterable
     * @throws ResolverError
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $data = $request->request->all();
        $data['left_side']['x'] = null;
        $violations = $this->validator->validate($data, $this->constraint());

        if (count($violations) > 0) {
            throw new ResolverError($violations);
        }

        $leftSideRaw = $request->request->get('left_side');
        $rightSideRaw = $request->request->get('right_side');

        $leftSide = Point::create((float)$leftSideRaw['x'], (float)$leftSideRaw['y']);
        $rightSide = Point::create((float)$rightSideRaw['x'], (float)$rightSideRaw['y']);

        $needToRunAsync = $request->request->getBoolean('run_async', false);

        yield new CreateSegmentRequest($leftSide, $rightSide, $needToRunAsync);
    }
}