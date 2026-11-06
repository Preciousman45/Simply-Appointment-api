<?php

declare(strict_types=1);

namespace user\Exceptions;

use DomainException;
use Mezzio\ProblemDetails\Exception\CommonProblemDetailsExceptionTrait;
use Mezzio\ProblemDetails\Exception\ProblemDetailsExceptionInterface;
use Throwable;

class UserExceptions extends DomainException implements ProblemDetailsExceptionInterface
{
    use CommonProblemDetailsExceptionTrait;

    public static function inputDataNotFound(string $e): self
    {
        $detail = "{$e} was not included, please add it to the data";
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'Bad Request';
        $exception->detail = $detail;

        return $exception;
    }

    public static function noRoleFound(): self
    {
        $detail = 'No role found with this role id';
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    public static function userNotFound(): self
    {
        $detail = 'This user is not found';
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    public static function staffNotFound(): self
    {
        $detail = "This staff can't be found";
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    public static function fromUserThrowable(Throwable $e): self
    {
        $detail = $e->getMessage();
        $exception = new self($detail, 0, $e);
        $exception->status = 500;
        $exception->type = '';
        $exception->title = 'Internal Server Error';
        $exception->detail = $detail;

        return $exception;
    }
}
