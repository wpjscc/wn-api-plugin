<?php 

use Laravel\Sanctum\Sanctum;


return [
    // This contains the Laravel Packages that you want this plugin to utilize listed under their package identifiers
    'packages' => [
        'wpjscc/api' => [
            'config_namespace' => 'api',
            'config' => [
                'prefix' => 'api',
            ]
        ],
        'laravel/auth' => [
            'providers' => [
                \Illuminate\Auth\AuthServiceProvider::class
            ],
            'aliases' => [

            ],
            'middlewareAliases' => [
                'auth' => \Wpjscc\Api\Classes\Authenticate::class,
            ],
            'config_namespace' => 'auth',
            'config' => [
                'defaults' => [
                    'guard' => 'sanctum',
                ],
                'guards' => [
                    'sanctum' => [
                        'driver' => 'sanctum',
                        'provider' => 'users',
                    ],
                ],
                'providers' => [
                    'users' => [
                        'driver' => 'eloquent',
                        'model' => \Wpjscc\Api\Models\User::class,
                    ],
                ],
            ]
        ],
        'laravel/sanctum' => [
            // Service providers to be registered by your plugin
            'providers' => [
                Laravel\Sanctum\SanctumServiceProvider::class,
            ],

            // Aliases to be registered by your plugin in the form of $alias => $pathToFacade
            'aliases' => [

            ],

            // The namespace to set the configuration under. For this example, this package accesses it's config via config('purifier.' . $key), so the namespace 'purifier' is what we put here
            'config_namespace' => 'sanctum',//sanctum

            // The configuration file for the package itself. Start this out by copying the default one that comes with the package and then modifying what you need.
            'config' => [

                'support_default_route' => env('SANCTUM_SUPPORT_DEFAULT_ROUTE', true),

                /*
                |--------------------------------------------------------------------------
                | Stateful Domains
                |--------------------------------------------------------------------------
                |
                | Requests from the following domains / hosts will receive stateful API
                | authentication cookies. Typically, these should include your local
                | and production domains which access your API via a frontend SPA.
                |
                */
            
                'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
                    '%s%s',
                    'localhost,localhost:3000,192.168.1.9,192.168.1.9:8081,127.0.0.1,127.0.0.1:8000,::1',
                    Sanctum::currentApplicationUrlWithPort()
                ))),
            
                /*
                |--------------------------------------------------------------------------
                | Sanctum Guards
                |--------------------------------------------------------------------------
                |
                | This array contains the authentication guards that will be checked when
                | Sanctum is trying to authenticate a request. If none of these guards
                | are able to authenticate the request, Sanctum will use the bearer
                | token that's present on an incoming request for authentication.
                |
                */
            
                'guard' => [],
                // 'guard' => ['web'],
            
                /*
                |--------------------------------------------------------------------------
                | Expiration Minutes
                |--------------------------------------------------------------------------
                |
                | This value controls the number of minutes until an issued token will be
                | considered expired. If this value is null, personal access tokens do
                | not expire. This won't tweak the lifetime of first-party sessions.
                |
                */
            
                'expiration' => null,
            
                /*
                |--------------------------------------------------------------------------
                | Sanctum Middleware
                |--------------------------------------------------------------------------
                |
                | When authenticating your first-party SPA with Sanctum you may need to
                | customize some of the middleware Sanctum uses while processing the
                | request. You may change the middleware listed below as required.
                |
                */
            
                'middleware' => [
                    'encrypt_cookies' => Wpjscc\Api\Http\Middleware\EncryptCookies::class,
                ],
            
            ],
        ],

    ],
];
