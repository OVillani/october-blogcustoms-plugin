<?php

namespace Synder\BlogCustoms\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Behaviors\RelationController;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use October\Rain\Database\Builder;


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
     * Required Permissions
     * 
     * @var array
     */
    public $requiredPermissions = [
        'rainlab.blog.access_other_posts', 
        'rainlab.blog.access_posts'
    ];
    
    /**
     * Constructor
     */
    public function __construct()
    {
        if (request()->get('list', 'customs') === 'posts') {
            $this->listConfig = 'config_list_posts.yaml';
        }

        parent::__construct();
        BackendMenu::setContext('RainLab.Blog', 'blog', 'customs');
    }

    /**
     * Execute Logic before Rendering
     * 
     * @return void
     */
    public function beforeDisplay()
    {
    }

    /**
     * Index Action
     *
     * @return void
     */
    public function index()
    {
        $this->vars['list_type'] = $this->listConfig === 'config_list.yaml'? 'customs': 'posts';

        $this->asExtension('ListController')->index();
    }

    /**
     * Remove multiple Customs
     *
     * @return mixed
     */
    public function onBulkDelete()
    {
        if (request()->get('list', 'customs')) {
            //dd(post('checked'));  [name]-[type]
        } else {
            //dd(post('checked'));  [post_id]
        }
    }

    /**
     * Extended List Query
     *
     * @param \October\Rain\Database\Builder $query
     * @return void
     */
    public function listExtendQuery(Builder $query)
    {
        $page = intval(post('page', 1));
        $offset = 25 * ($page);

        if (request()->get('list', 'customs') !== 'posts') {
            $query->getQuery()->selects = [];
            $query->select('synder_blogcustoms.name as name', 'synder_blogcustoms.type as type');
            $query->selectRaw('COUNT(synder_blogcustoms.id) AS count');
            $query->groupBy('synder_blogcustoms.name', 'synder_blogcustoms.type');
            $query->limit(25);
            $query->offset($offset);
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function listInjectRowClass($record, $definition = null)
    {
        if (request()->get('list', 'customs') === 'posts' && !$record->published) {
            return 'safe disabled';
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function listOverrideColumnValue($record, $column, $value)
    {
        if ($column === 'customs_count') {
            return count($record->customs);
        }
    }
}
