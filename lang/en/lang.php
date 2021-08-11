<?php 

return [
    'plugin' => [
        'name' => 'Simple Custom Datasets',
        'description' => 'Simple custom datasets for the RainLab.Blog extension.'
    ],

    'fields' => [
        'tab' => 'Custom Data',
        'label' => 'Custom Data Sets',
        'prompt' => 'Add custom dataset',
        'name' => 'data_name',
        'value' => 'Data Value'
    ],

    'formwidget' => [
        'prompt' => 'Add custom dataset',
        'name' => 'data_name',
        'type' => 'Data Type',
        'types' => [
            'text' => 'Text',
            'number' => 'Number',
            'array' => 'Array',
        ],
        'value' => 'Data Value',
        'help_names' => 'Custom Data-Names MUST consist of alphanumeric and underscore characters only and CANNOT start with a number.',
        'help_array_type' => 'Custom Data-Type "Array" uses a comma to split the single items.'
    ],

    'admin' => [
        'list' => [
            'toolbar_create' => 'Create Default',
            'toolbar_search_prompt' => 'Search Custom',
            'custom_name' => 'Custom Name',
            'custom_type' => 'Custom Type',
            'posts_count' => 'Posts Count'
        ]
    ]
];
