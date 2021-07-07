<?php

declare(strict_types=1);

namespace ArchiTools\Request;

use App\Infrastructure\Exception\MissingRequestParameterException;
use Exception;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequestCommandResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    abstract public function supports(Request $request, ArgumentMetadata $argument): bool;

    /**
     * @throws ReflectionException
     * @throws MissingRequestParameterException
     * @throws \JsonException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $routeParams = $request->attributes->get('_route_params');
        $requestContent = $request->getContent();

        if ($this->routeContainsParameter($routeParams)) {
            $content = ObjectBuilder::buildFromRequest($request);
            $requestContent = $this->serializer->serialize($content, 'json');
        }

        PropertyChecker::checkProperties($argument->getType(), $request);

        $object = $this->serializer->deserialize($requestContent, $argument->getType(), 'json');

        if (!empty($this->getErrors($object))) {
            throw new Exception('Data validation failed.');
        }

        yield $object;
    }

    private function routeContainsParameter(array $routeParams): bool
    {
        return isset($routeParams['id']) || isset($routeParams['uuid']) || isset($routeParams['type']);
    }

    protected function getErrors(object $command): array
    {
        $validation = $this->validator->validate($command);
        $errors = [];
        if ($validation->count() > 0) {
            /** @var ConstraintViolation $error */
            foreach ($validation as $error) {
                $errors[$error->getPropertyPath()] = $error->getMessage();
            }
        }

        return $errors;
    }
}
