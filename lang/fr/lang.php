<?php

return [
    'controllers' => [
        'logsfs' => [
            'label' => 'Log Sfs',
        ],
    ],
    'global' => [
        'authorize' => 'Autoriser',
        'authorize_button' => 'Connecter',
        'authorize_comment' => 'Commentaires autorisations',
        'authorized' => 'SalesForce est connecté !',
        'authorized_comment' => 'Vous pouvez vous synchroniser',
        'error' => 'Erreur',
        'error_comment' => 'Commentaire erreur',
        'unauthorized' => 'SalesForce n\'est pas connecté !',
        'unauthorized_comment' => 'Vous devez connecter SalesForce',
    ],
    'job' => [
        'title' => 'Requête SalesForce',
    ],
    'logsf' => [
        'errors' => 'Erreurs',
        'import_indicator' => 'Chargement des données en cours',
        'manual_auto' => 'Forcer l\'import automatique',
        'manual_popup' => 'Choisir les imports et les options',
        'name' => 'Nom de l\'import',
        'nb' => 'Nombre de lignes',
        'nb_updated_rows' => 'Lignes enregistrées',
        'popup_title' => 'Rapport',
        'query' => 'Requête',
        'sf_total_size' => 'Lignes venant de SalesForce',
    ],
    'menu' => [
        'category' => 'Synchronisation',
        'logsf' => 'Log SalesForce',
        'logsf_description' => 'Connexion et lancement de synchro manuelle',
        'settings' => 'Options de SalesForce',
        'settings_description' => 'Option et configuration des imports et exports',
    ],
    'models' => [
        'logsf' => [
            'label' => 'Log Sf',
        ],
    ],
    'settings' => [
        'active_imports' => 'Choisissez les imports actifs',
        'exports_authorized' => 'Exports autorisés',
        'imports_authorized' => 'Imports autorisés',
        'oldest_date' => 'Date de réinitialisation',
    ],
];
