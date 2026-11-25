<?php
declare(strict_types=1);

namespace App\EventListener;


use App\DTO\ErrorDTO;
use App\Exception\ApplicationException;
use App\Exception\CommandFieldValidationException;
use App\Exception\ResourceAlreadyExistsException;
use App\Exception\ResourceNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


#[AsEventListener(event: ExceptionEvent::class)]
class ExceptionListener {

    public function __construct(private LoggerInterface $logger) {
    }


    public function __invoke(ExceptionEvent $event): void {
        $ex = $event->getThrowable();

        if ($ex instanceof CommandFieldValidationException) {
            $this->logger->warning($ex->getMessage(), ['exception' => $ex]);
            $event->setResponse(new JsonResponse($ex->getErrors(), Response::HTTP_BAD_REQUEST));
            return;
        }

        if ($ex instanceof ResourceNotFoundException) {
            $this->logger->warning($ex->getMessage());
            $error = new ErrorDTO('error.notfound', $ex->getMessage());
            $event->setResponse(new JsonResponse($error, Response::HTTP_NOT_FOUND));
            return;
        }

        if ($ex instanceof AccessDeniedException) {
            $this->logger->warning($ex->getMessage());
            $error = new ErrorDTO('error.user.access.forbidden', $ex->getMessage());
            $event->setResponse(new JsonResponse($error, Response::HTTP_FORBIDDEN));
            return;
        }

        if ($ex instanceof ResourceAlreadyExistsException) {
            $this->logger->warning($ex->getMessage());
            $error = new ErrorDTO('error.unique', $ex->getMessage());
            $event->setResponse(new JsonResponse($error, Response::HTTP_UNPROCESSABLE_ENTITY));
            return;
        }

        if ($ex instanceof ApplicationException) {
            $this->logger->error($ex->getMessage());
            $error = new ErrorDTO("error.server", $ex->getMessage());
            $event->setResponse(new JsonResponse($error, Response::HTTP_INTERNAL_SERVER_ERROR));
            return;
        }

        if ($ex instanceof HttpExceptionInterface) {
            $error = null;

            if (401 === $ex->getStatusCode()) {
                $this->logger->warning($ex->getMessage());
                $error = new ErrorDTO("error.user.unauthorized.forbidden", $ex->getMessage());
            } else {
                $this->logger->error($ex->getMessage());
                $error = new ErrorDTO("error.http", $ex->getMessage());
            }

            $event->setResponse(new JsonResponse($error, $ex->getStatusCode()));
            return;
        }

        // Аналог @ExceptionHandler(Exception.class)
        $this->logger->error($ex->getMessage(), ['exception' => $ex]);
        $error = new ErrorDTO('error.unknown', 'An error has occurred. Please contact the Administrator');
        $event->setResponse(new JsonResponse($error, Response::HTTP_INTERNAL_SERVER_ERROR));
    }
}
