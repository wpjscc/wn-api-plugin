<?php


/**
 * 验证是否是中国验证码.
 *
 * @param  string  $number
 * @return bool
 */
function validateChinaPhoneNumber(string $number): bool
{
    return (bool) preg_match('/^(\+?0?86\-?)?1[3-9]\d{9}$/', $number);
}

/**
 * 验证用户名是否合法.
 *
 * @param  string  $username
 * @return bool
 */
function validateUsername(string $username): bool
{
    return (bool) preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $username);
}

/**
 * Get user login field.
 *
 * @param  string  $login
 * @param  string  $default
 * @return string
 *
 * @author Seven Du <shiweidu@outlook.com>
 */
function username(string $login, string $default = 'id'): string
{
    $map = [
        'email' => filter_var($login, FILTER_VALIDATE_EMAIL),
        'mobile' => validateChinaPhoneNumber($login),
        'username' => validateUsername($login),
    ];

    foreach ($map as $field => $value) {
        if ($value) {
            return $field;
        }
    }

    return $default;
}
