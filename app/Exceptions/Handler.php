<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Litepie\Support\Facades\Theme;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    public function report(Throwable $e)
    {
        return parent::report($e);
    }

    public function render($request, Throwable $e)
    {
        return parent::render($request, $e);
    }

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        $status = $e->getStatusCode();

        if (view()->exists("public::errors.{$status}")) {
            $theme = Theme::uses('public')->layout('default');
            return $theme->of("public::errors.{$status}", ['exception' => $e])->render();
        } else {
            return $this->convertExceptionToResponse($e);
        }

    }

}
