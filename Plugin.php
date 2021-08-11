<?php

namespace Synder\BlogCustoms;

use Backend;
use Event;
use Backend\Classes\NavigationManager;
use Backend\Widgets\Form;
use October\Rain\Database\Model;
use RainLab\Blog\Controllers\Posts;
use RainLab\Blog\Models\Post;
use System\Classes\PluginBase;

use Synder\BlogCustoms\FormWidgets\CustomRepeater;
use Synder\BlogCustoms\Models\Custom;


class Plugin extends PluginBase
{
    /**
     * Plugin dependencies
     *
     * @var array
     */
    public $require = ['RainLab.Blog'];

    /**
     * Plugin Details
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'synder.blogcustoms::lang.plugin.name',
            'description' => 'synder.blogcustoms::lang.plugin.description',
            'author'      => 'Synder <october@synder.dev>',
            'homepage'    => 'https://octobercms.com/plugin/synder-blogcustoms'
        ];
    }

    /**
     * Register Form Widgets
     *
     * @return void
     */
    public function registerFormWidgets()
    {
        return [
            CustomRepeater::class => 'synder-customrepeater'
        ];
    }

    /**
     * Boot Plugin
     *
     * @return void
     */
    public function boot()
    {
        Post::extend(fn($model) => $this->extendPostModel($model));
        Posts::extendFormFields(fn(...$vars) => $this->extendPostController(...$vars));
    }

    /**
     * Extend Post Model
     *
     * @param \RainLab\Blog\Models\Post $model
     * @return void
     */
    protected function extendPostModel(Post $model)
    {
        $model->addJsonable('customs');

        // Add Dynamic Method
        $model->addDynamicMethod('customs', function() {
            return new Custom;
        });

        // Collect Customs Post and Process
        $model->bindEvent('model.beforeSave', function() use ($model) {
            if ((post('Post')['customdata'] ?? 'no') === 'yes') {
                unset($model->attributes['customs']);
            }
        });
        $model->bindEvent('model.afterSave', function() use ($model) {
            if ((post('Post')['customdata'] ?? 'no') === 'yes') {
                Custom::storeFromArray(post('Post')['customs'] ?? [], $model);
            }
        });
        $model->bindEvent('model.afterDelete', function() use ($model) {
            if ((post('Post')['customdata'] ?? 'no') === 'yes') {
                Custom::where('post_id', $model->id)->delete();
            }
        });

        // Pass Customs to Model
        $model->bindEvent('model.afterFetch', function() use ($model) {
            if (!$model->id) {
                return;
            }
            $result = Custom::where('post_id', $model->id)->get();

            $customs = [];
            foreach ($result AS $row) {
                $value = $row->value;

                if ($value === '') {
                    $value = null;
                } else {
                    if ($row->type === 'number') {
                        $value = strpos(strval($value), '.') >= 0? floatval($value): intval($value);
                    } else if ($row->type === 'array') {
                        $value = array_map(fn($val) => trim($val), explode(',', $value));
                    }
                }

                $customs[$row->name] = $value;
            }

            $model->addDynamicProperty('customs', $customs);
            $model->addDynamicProperty('rawCustoms', $result);
        });
    }

    /**
     * Extend Post Controller
     *
     * @param \Backend\Widgets\Form $form
     * @param \October\Rain\Database\Model $model
     * @param [type] $context
     * @return void
     */
    protected function extendPostController(Form $form, Model $model, $context)
    {
        if (!$model instanceof Post) {
            return;
        }
        if ($form->isNested) {
            return;
        }

        $form->addSecondaryTabFields([
            'customs' => [
                'type' => 'synder-customrepeater',
                'label' => 'synder.blogcustoms::lang.fields.label',
                'prompt' => 'synder.blogcustoms::lang.fields.prompt',
                'tab' => 'synder.blogcustoms::lang.fields.tab'
            ]
        ]);
    }

    /**
     * Register Plugin Navigation
     *
     * @return void
     */
    public function registerNavigation(): void
    {
        Event::listen('backend.menu.extendItems', function (NavigationManager $manager) {
            $manager->addSideMenuItems('RainLab.Blog', 'blog', [
                'customs' => [
                    'label' => 'Custom Fields',
                    'icon'  => 'icon-cubes',
                    'code'  => 'synder-blogcustoms',
                    'owner' => 'RainLab.Blog',
                    'url'   => Backend::url('synder/blogcustoms/customs')
                ]
            ]);
        });
    }
}
