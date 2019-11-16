<?php

namespace Bedard\Saas\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

/**
 * Products Back-end Controller.
 */
class Plans extends Controller
{
    public $formConfig = 'config_form.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $listConfig = 'config_list.yaml';

    public $registerPermissions = [
        'bedard.saas.access_products',
    ];

    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Saas', 'saas', 'products');
    }

    public function listExtendQuery($query)
    {
        $query->withCount('schedules');
    }
}
