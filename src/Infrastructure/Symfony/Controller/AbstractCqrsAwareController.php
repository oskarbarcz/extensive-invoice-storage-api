<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractCqrsAwareController extends AbstractController
{
    protected MessageBusInterface $messageBus;
    protected ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator, MessageBusInterface $messageBus)
    {
        $this->validator = $validator;
        $this->messageBus = $messageBus;
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

    protected function handleCommand(object $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $exception) {
            $nested = $exception->getNestedExceptions();
            $exception = array_pop($nested);
            if (null !== $exception) {
                throw $exception;
            }
        }
    }
}
