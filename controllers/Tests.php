<?php namespace Wpjscc\Api\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Tests extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'wpjscc.api.tests' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Wpjscc.Api', 'tests');
    }
}
