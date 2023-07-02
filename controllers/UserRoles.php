<?php namespace Wpjscc\Api\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Response;
use View;

/**
 * User Roles Backend Controller
 */
class UserRoles extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\RelationController::class,
    ];

    /**
     * @var array Permissions required to view this page.
     */
    public $requiredPermissions = ['manage_users'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Winter.User', 'user', 'userroles');

        /*
         * Only super users can access
         */
        $this->bindEvent('page.beforeDisplay', function () {
            if (!$this->user->isSuperUser()) {
                return Response::make(View::make('backend::access_denied'), 403);
            }
        });
    }
    
}
