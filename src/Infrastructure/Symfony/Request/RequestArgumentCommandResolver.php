<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Request;

use App\Infrastructure\Exception\MissingRequestParameterException;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestArgumentCommandResolver implements ArgumentValueResolverInterface
{
    private const COMMAND_NAMESPACE = 'App\\Application\\Command\\';

    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $supportedCommands = array_filter(
            get_declared_classes(),
            static fn(string $className) => str_contains($className, self::COMMAND_NAMESPACE)
        );

        return in_array($argument->getType(), $supportedCommands, true);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $routeParams = $request->attributes->get('_route_params');
        $requestContent = $request->getContent();

        if ($request->get('file') && 0 === $request->files->count()) {
            throw new FileException('validation.files.invalid');
        }

        if ($request->files->count()) {
            $files = $request->files->all();
            if (array_key_exists('image', $files)) {
                $image = null;
                if ($files['image'] instanceof UploadedFile) {
                    $image = $files['image'];
                }
//                        yield new UploadImageCommand($image);
                return;
            }
            if (array_key_exists('file', $files)) {
                $file = null;
                if ($files['file'] instanceof UploadedFile) {
                    $file = $files['file'];
                }
//                        yield new UploadFileCommand($file, $routeParams['type'] ?? null);
                return;
            }
        }

        if ($this->routeContainsParameter($routeParams)) {
            if (false === empty($requestContent)) {
                $content = $this->serializer->decode(
                    $requestContent,
                    'json',
                    [
                        JsonDecode::ASSOCIATIVE => false,
                    ]
                );
                $content->uuid = $routeParams['id'] ?? $routeParams['uuid'] ?? null;
            } else {
                $content = (object)['uuid' => $routeParams['id'] ?? $routeParams['uuid'] ?? null];
                if (isset($routeParams['type'])) {
                    $content->type = $routeParams['type'];
                }
            }

            $requestContent = $this->serializer->serialize($content, 'json');
        }

        $this->checkProperties($argument->getType(), $requestContent);

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

    /** @throws ReflectionException|MissingRequestParameterException */
    private function checkProperties(string $argumentType, string $jsonRequest): void
    {
        $argumentTypeReflection = new ReflectionClass($argumentType);
        $properties = $argumentTypeReflection->getProperties();

        foreach ($properties as $property) {
            if ($this->isParameterMissing($property, $jsonRequest)) {
                throw new MissingRequestParameterException($property->getName());
            }
        }
    }

    private function isParameterMissing(ReflectionProperty $property, string $jsonRequest): bool
    {
        return false === $property->getType()->allowsNull()
            && false === mb_strpos($jsonRequest, sprintf('"%s"', $property->getName()));
    }

    protected function getErrors(object $command): array
    {
        $validation = $this->validator->validate($command, null, null);
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
