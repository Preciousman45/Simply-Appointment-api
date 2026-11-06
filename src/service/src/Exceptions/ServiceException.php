<?php

declare(strict_types=1);

namespace service\Exceptions;

use DomainException;
use Mezzio\ProblemDetails\Exception\CommonProblemDetailsExceptionTrait;
use Mezzio\ProblemDetails\Exception\ProblemDetailsExceptionInterface;
use Throwable;

class ServiceException extends DomainException implements ProblemDetailsExceptionInterface
{
    use CommonProblemDetailsExceptionTrait;

    public static function nameNotFound(): self
    {
        $detail = 'No name was added to this service, please add a name to the service';
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'No service name added';
        $exception->detail = $detail;

        return $exception;
    }

    public static function descriptionNotFound(): self
    {
        $detail = 'No description was added to this service, please add a description to the service';
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'No description added';
        $exception->detail = $detail;

        return $exception;
    }

    public static function priceNotFound(): self
    {
        $detail = 'No price was added to this service, please add a price to the service';
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'No price added';
        $exception->detail = $detail;

        return $exception;
    }

    public static function durationNotFound(): self
    {
        $detail = 'No duration was added to this service, please add a duration to the service';
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'No duration added';
        $exception->detail = $detail;

        return $exception;
    }

    public static function noServiceAdded(): self
    {
        $detail = 'No service id added to the request';
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'No service ID added';
        $exception->detail = $detail;

        return $exception;
    }

    public static function serviceNotFound(): self
    {
        $detail = 'No service found with this service ID';
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    public static function fromServiceThrowable(Throwable $e): self
    {
        $detail = $e->getMessage();
        $exception = new self($detail, 0, $e);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'Bad Request';
        $exception->detail = $detail;

        return $exception;
    }
}
