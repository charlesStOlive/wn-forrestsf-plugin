<?php

return [
    'controllers' => [
        'logsfs' => [
            'label' => 'SalesForce Logs',
        ],
    ],
    'global' => [
        'authorize' => 'Authorize',
        'authorize_button' => 'Connect',
        'authorize_comment' => 'Authorization comments',
        'authorized' => 'SalesForce is connected!',
        'authorized_comment' => 'You can synchronize',
        'error' => 'Error',
        'error_comment' => 'Error comment',
        'unauthorized' => 'SalesForce is not connected!',
        'unauthorized_comment' => 'You need to connect SalesForce',
    ],
    'job' => [
        'title' => 'SalesForce Request',
    ],
    'logsf' => [
        'errors' => 'Errors',
        'import_indicator' => 'Loading data in progress',
        'manual_auto' => 'Force automatic import',
        'manual_popup' => 'Choose imports and options',
        'name' => 'Import name',
        'nb' => 'Number of rows',
        'nb_updated_rows' => 'Rows saved',
        'popup_title' => 'Report',
        'query' => 'Query',
        'sf_total_size' => 'Rows from SalesForce',
    ],
    'menu' => [
        'category' => 'Synchronization',
        'logsf' => 'SalesForce Log',
        'logsf_description' => 'Connection and launch of manual sync',
        'settings' => 'SalesForce Options',
        'settings_description' => 'Options and configuration of imports and exports',
    ],
    'models' => [
        'logsf' => [
            'label' => 'SalesForce Log',
        ],
    ],
    'settings' => [
        'active_imports' => 'Choose active imports',
        'exports_authorized' => 'Authorized exports',
        'imports_authorized' => 'Authorized imports',
        'oldest_date' => 'Reset date',
    ],
];
