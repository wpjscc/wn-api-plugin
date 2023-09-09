<?php

namespace Wpjscc\Api\Exceptions;

use App;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as BaseNotFoundHttpException;


class Handler
{
    public static function register()
    {
        App::error(function(AuthenticationException $e){
            return response()->json([
                'status' => 'fail',
                'code' => 401,
                'msg' => $e->getMessage(),
                'data' => new \stdClass
            ]);
        });

        App::error(function(ValidationException $e){
            return response()->json([
                'status' => 'fail',
                'code' => 422,
                'msg' => $e->getMessage(),
                'data' => new \stdClass
            ]);
        });
        
        App::error(function(RuntimeException $e){
            return Handler::renderException($e);
        });

        App::error(function(ForbidException $e){
            return Handler::renderException($e);
        });

        App::error(function(NoAuthenticationException $e){
            return Handler::renderException($e);
        });

        App::error(function(NotFoundHttpException $e){
            return Handler::renderException($e);
        });

        App::error(function(BaseNotFoundHttpException $e){
            return Handler::renderException($e);
        });

        App::error(function(QueryException $e){
            return Handler::renderException($e);
        });
    }

    public static function renderException($e)
    {
        
        return response()->json([
            'status' => 'fail',
            'code' => $e->getCode()?:1,
            'msg' => config('app.debug') ? sprintf(
                '"%s" on line %s of %s',
                $e->getMessage(),
                $e->getLine(),
                $e->getFile()
            ):$e->getMessage(),
            'data' => new \stdClass
        ]);
    }
}
