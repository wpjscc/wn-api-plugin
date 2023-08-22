<?php

namespace Wpjscc\Api\Services;

class UserToService
{

    public static function userToService($user)
    {
        $currentAccessToken = $user->currentAccessToken();
        if ($currentAccessToken && $service = $currentAccessToken->service()->first()) {

            $code = $service->code;
            list($author, $plugin) = explode('.', $code);
            $pluginNamespace = '\\'. ucfirst($author) . '\\' . ucfirst($plugin);

            $userService = $pluginNamespace . '\\Services\\UserService';

            if (class_exists($userService) && method_exists($userService, 'setUser')) {
                $userService::setUser($user);
            }
            
        }
    }
}
