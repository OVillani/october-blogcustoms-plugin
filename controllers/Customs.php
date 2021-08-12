<?php

namespace Synder\BlogCustoms\Controllers;

use Flash;
use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Behaviors\RelationController;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use October\Rain\Database\Builder;

use Synder\BlogCustoms\Models\Custom;


class Customs extends Controller
{
    /**
     * Behaviours implemented by the controller
     *
     * @var array
     */
    public $implement = [
        FormController::class,
        ListController::class
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
     * Edit multiple Customs or Posts (Create URL for List selection)
     *
     * @return mixed
     */
    public function onBulkEdit()
    {
        if (request()->get('list', 'customs') === 'customs') {
            $customs = post('checked', []);

        } else {
            $posts = post('checked', []);

            return redirect('test');
        }
    }

    /**
     * Remove multiple Customs
     *
     * @return mixed
     */
    public function onBulkDelete()
    {
        if (request()->get('list', 'customs') === 'customs') {
            $customs = post('checked', []);
            if (!is_array($customs)) {
                Flash::error(e(trans('synder.blogcustoms::lang.admin.errors.invalid_args')));
                return;
            }

            // Validate All before Process
            $stacks = [];
            foreach ($customs AS $custom) {
                [$name, $type] = array_map('trim', explode('-', $custom));
                if (empty($name) || empty($type)) {
                    Flash::error(e(trans('synder.blogcustoms::lang.admin.errors.invalid_args')));
                    return;
                }
                $stacks[] = ['name' => $name, 'type' => $type];
            }

            // Process
            foreach ($stacks AS $stack) {
                Custom::where('name', '=', $stack['name'])->where('type', '=', $stack['type'])->delete();
            }
            Flash::success(e(trans('synder.blogcustoms::lang.admin.success.bulk_delete_customs')));
            return $this->listRefresh();
        } else {
            $posts = post('checked', []);
            if (!is_array($posts)) {
                Flash::error(e(trans('synder.blogcustoms::lang.admin.errors.invalid_args')));
                return;
            }

            // Process
            foreach ($posts AS $post) {
                Custom::where('post_id', '=', intval($post))->delete();
            }
            Flash::success(e(trans('synder.blogcustoms::lang.admin.success.bulk_delete_posts')));
            return $this->listRefresh();
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
            $query->selectRaw('COUNT(synder_blogcustoms.id) AS count, CONCAT_WS("-", synder_blogcustoms.name, synder_blogcustoms.type) as list_key');
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
