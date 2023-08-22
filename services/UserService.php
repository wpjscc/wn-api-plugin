<?php

namespace Wpjscc\Api\Services;

class UserService
{
    protected static $user;

    public static function setUser($user)
    {
        static::$user = $user;
    }

    public static function getUser()
    {
        return static::$user;
    }

    public static function getCurrentUser()
    {
        return static::$user;
    }

    
}
