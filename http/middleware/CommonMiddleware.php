<?php namespace Wpjscc\Api\Http\Middleware;

use Wpjscc\Api\Classes\ApiController;
use Auth;

class CommonMiddleware
{
    public function handle($request, $next)
    {
        $options = json_decode($request->header('options'), true);
        $ui = $options['ui'] ?? '';
        if ($ui || $options) {
            ApiController::$ui = $ui;
            ApiController::$options= $options;
        }
        if ($user = $request->user()) {
            Auth::setUser($user);
        }

        return $next($request);
    }

}
