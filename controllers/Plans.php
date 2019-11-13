<?php

namespace Bedard\Saas\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

/**
 * Plans Back-end Controller.
 */
class Plans extends Controller
{
    public $formConfig = 'config_form.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $listConfig = 'config_list.yaml';

    public $registerPermissions = [
        'bedard.saas.access_plans',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Saas', 'saas', 'plans');
    }
}