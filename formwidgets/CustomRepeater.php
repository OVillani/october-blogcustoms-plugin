<?php

namespace Synder\BlogCustoms\FormWidgets;

use Backend\Classes\FormWidgetBase;


class CustomRepeater extends FormWidgetBase
{
    //
    // Configurable properties
    //

    /**
     * @var string Prompt text for adding new items.
     */
    public $prompt = 'synder.blogcustoms::lang.formwidget.prompt';

    
    //
    // Object properties
    //

    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'synder-customrepeater';


    /**
     * @inheritDoc
     */
    protected function loadAssets()
    {
        $this->addCss('css/customrepeater.css', 'core');
        $this->addJs('js/customrepeater.js', 'core');
    }

    /**
     * @inheritDoc
     */
    public function render()
    {

        $this->vars['prompt'] = $this->prompt;
        $this->vars['customs'] = $this->model->rawCustoms;

        return $this->makePartial('field_customrepeater');
    }
}
