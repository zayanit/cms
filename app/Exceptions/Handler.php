<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Litepie\Support\Facades\Theme;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        AuthorizationException::class,
        HttpExceptionInterface::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    public function render($request, Throwable $e): \Symfony\Component\HttpFoundation\Response
    {
        return parent::render($request, $e);
    }

    protected function renderHttpException(HttpExceptionInterface $e): \Symfony\Component\HttpFoundation\Response
    {
        $status = $e->getStatusCode();

        if (view()->exists("public::errors.{$status}")) {
            $theme = Theme::uses('public')->layout('default');
            return $theme->of("public::errors.{$status}", ['exception' => $e])->render();
        }

        return $this->convertExceptionToResponse($e);
    }
}
