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
            'title' => 'Manage Custom Fields',
            'title_menu' => 'Customs',
            'empty' => 'No Custom Fields yet',
            'toolbar_create' => 'Create New',
            'list_by_customs' => 'List by Customs',
            'list_by_posts' => 'List by Posts',
            'edit_selected' => 'Edit Customs',
            'delete_selected' => 'Delete Customs',
            'delete_confirm' => 'Are you sure you want to delete all selected custom fields? This step cannot be undone.',
            'delete_confirm_posts' => 'Are you sure you want to delete all custom fields from the selected posts? This step cannot be undone.',
            'toolbar_search_prompt' => 'Search...',
            'custom_name' => 'Custom Name',
            'custom_type' => 'Custom Type',
            'posts_count' => 'Posts Count'
        ],
        'errors' => [
            'invalid_args' => 'The passed parameters are invalid.'
        ],
        'success' => [
            'bulk_delete_customs' => 'All selected custom fields have been deleted successfully.',
            'bulk_delete_posts' => 'All custom fields of all selected posts have been deleted successfully.'
        ]
    ]
];
