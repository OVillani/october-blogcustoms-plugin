<?php

namespace Synder\BlogCustoms\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Behaviors\RelationController;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;


class Customs extends Controller
{
    /**
     * Behaviours implemented by the controller
     *
     * @var array
     */
    public $implement = [
        FormController::class,
        ListController::class,
        RelationController::class
    ];

    /**
     * Form Behaviour
     *
     * @var string
     */
    public $formConfig = 'config_form.yaml';

    /**
     * List Behaviour
     *
     * @var string
     */
    public $listConfig = 'config_list.yaml';

    /**
     * Relation Behaviour
     *
     * @var string
     */
    public $relationConfig = 'config_relation.yaml';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RainLab.Blog', 'blog', 'customs');
    }

    public function listExtendQuery($query)
    {
        $query->getQuery()->selects = [];
        $query->select('synder_blogcustoms.name as name', 'synder_blogcustoms.type as type');
        $query->selectRaw('COUNT(synder_blogcustoms.id) AS count');
        $query->groupBy('synder_blogcustoms.name', 'synder_blogcustoms.type');
    }
}
