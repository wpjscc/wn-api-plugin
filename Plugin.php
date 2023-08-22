<?php namespace Wpjscc\Api;

use System\Classes\PluginBase;
use Config;
use Illuminate\Foundation\AliasLoader;
use Wpjscc\Api\Exceptions\Handler;
use Laravel\Sanctum\Sanctum;
use Wpjscc\Api\Models\UserRole;
use Backend\Classes\NavigationManager;
use Backend;
use BackendMenu;
use Event;
class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function register()
    {
        if (!$this->app->runningInBackend()) {
            $this->bootPackages();
        }

        $this->registerConsoleCommand('create.apicontroller', \Wpjscc\Api\Console\CreateApiController::class);
        $this->registerConsoleCommand('create.apimodel', \Wpjscc\Api\Console\CreateApiModel::class);
        
        Event::listen('backend.menu.extendItems', function (\Backend\Classes\NavigationManager $navigationManager) {
            $navigationManager->addSideMenuItems('Winter.User', 'user', [
                'userroles' => [
                    'label'       => 'backend::lang.user.role.list_title',
                    'icon'        => 'icon-apple',
                    'url'         => Backend::url('wpjscc/api/userroles'),
                    'permissions' => ['winter.users.access_roles']
                ]
            ]);
        });
        
       

    }

    public function boot()
    {
        $this->extendUser();

        if (!$this->app->runningInBackend()) {
            Handler::register();
            Sanctum::ignoreMigrations();
            Sanctum::usePersonalAccessTokenModel(\Wpjscc\Api\Models\PersonalAccessToken::class);

            // laravel Sanctum
            $this->app['Illuminate\Contracts\Http\Kernel']->prependMiddlewareToGroup('api', \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class);
            // 公共中间件
            $this->app['Illuminate\Contracts\Http\Kernel']->prependMiddlewareToGroup('api', \Wpjscc\Api\Http\Middleware\CommonMiddleware::class);
        }
       
    }

    

    public function extendUser()
    {
        if (class_exists(\Winter\User\Models\User::class)){
            \Winter\User\Models\User::extend(function ($model) {
                $model->belongsToMany['roles'] = [UserRole::class, 'table' => 'users_roles'];
                
            });

            \Winter\User\Controllers\Users::extendFormFields(function($form, $model, $context)
            {
                if (!$model instanceof \Winter\User\Models\User) {
                    return;
                }

                $form->addTabFields([
                    'roles' => [
                        'tab' => 'backend::lang.user.role.name',
                        'label'   => 'backend::lang.user.role.name',
                        'commentAbove' => 'backend::lang.user.role.name_comment',
                        'context' => ['create', 'update'],
                        'type' => 'relation',
                        'nameFrom' => 'name'
                    ],
                ]);
                $form->addTabFields([
                    'permissions' => [
                        'tab' => 'backend::lang.user.permissions',
                        'type' => 'Backend\FormWidgets\PermissionEditor',
                        'trigger' => [
                            'action' => 'disable',
                            'field' => 'is_superuser',
                            'condition' => 'checked'
                        ]
                    ]
                ]);

            });

        }
    }

    protected function bootPackages()
    {
        // Get the namespace of the current plugin to use in accessing the Config of the plugin
        $pluginNamespace = str_replace('\\', '.', strtolower(__NAMESPACE__));

        // Instantiate the AliasLoader for any aliases that will be loaded
        $aliasLoader = AliasLoader::getInstance();

        // Get the packages to boot
        $packages = Config::get($pluginNamespace . '::packages');

        // Boot each package
        foreach ($packages as $name => $options) {
            // Setup the configuration for the package, pulling from this plugin's config
            if (!empty($options['config']) && !empty($options['config_namespace'])) {
                $config = Config::get($options['config_namespace'], []) ?: [];
                Config::set($options['config_namespace'], array_merge($config, $options['config']));
            }

            // Register any Service Providers for the package
            if (!empty($options['providers'])) {
                foreach ($options['providers'] as $provider) {
                    $this->app->register($provider);
                }
            }

            // Register any Aliases for the package
            if (!empty($options['aliases'])) {
                foreach ($options['aliases'] as $alias => $path) {
                    $aliasLoader->alias($alias, $path);
                }
            }

            if (!empty($options['middlewareAliases'])) {
                $router = $this->app['router'];
                $method = method_exists($router, 'aliasMiddleware') ? 'aliasMiddleware' : 'middleware';
                foreach ($options['middlewareAliases'] as $alias => $middleware) {
                    $router->$method($alias, $middleware);
                }
            }
        }
    }

    public function registerValidationRules()
    {
        return [
            'cn_phone' => function ($attribute, $value, $parameters, $validator) {
                return validateChinaPhoneNumber($value);
            },
            'username' => function ($attribute, $value, $parameters, $validator) {
                return validateUsername($value);
            },
        ];
    }
}
