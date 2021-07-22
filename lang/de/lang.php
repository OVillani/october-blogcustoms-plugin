<?php 

return [
    'plugin' => [
        'name' => 'Simple Custom Datasets',
        'description' => 'Benutzerdefinierte Datensätze für die RainLab.Blog Erweiterung.'
    ],

    'fields' => [
        'tab' => 'Benutzerdefinierte Daten',
        'label' => 'Benutzerdefinierte Datensätze',
        'prompt' => 'Füge einen Datensatz hinzu',
        'name' => 'daten_name',
        'value' => 'Datenwert'
    ],

    'formwidget' => [
        'prompt' => 'Füge einen Datensatz hinzu',
        'name' => 'daten_name',
        'type' => 'Datentyp',
        'types' => [
            'text' => 'Text',
            'number' => 'Nummer',
            'array' => 'Array',
        ],
        'value' => 'Datenwert',
        'help_names' => 'Benutzerdefinierte Datennamen DÜRFEN nur aus alphanumerischen Zeichen und Unterstrichen bestehen und DÜRFEN nicht mit einer Nummer starten.',
        'help_array_type' => 'Der Datentyp "Array" nutzt das Komma um die einzelnen Datenwerte zu trennen.'
    ]
];
