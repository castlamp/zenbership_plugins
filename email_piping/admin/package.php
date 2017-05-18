<?php

return array(

    // -----------------------------------
    // General information
    'permission' => 'email_piping',

    'menu' => 'E-Mail Piping',

    'activity_feed' => array(
        'message' => '%notes%',
        'link' => 'email_piping-view',
        'type' => 'popup_large',
    ),

    // -----------------------------------
    // Secondary links on filters menu.
    'secondary_links' => array(
        /*
        array(
            'type' => 'popup',
            'act' => 'add',
            'name' => 'Create',
        ),
        */
    ),


    // -----------------------------------
    // Tables
    'zen_plugin_myTableName' => array(

        'force_headings' => array(
            // 'date',
            // 'field1',
            // 'field2',
        ),

        'order' => 'date',

        'dir' => 'DESC',

        'display' => '50',

        'force_filters' => array(

        ),

        'filters' => array(
           // 'date_confirmed:zen_plugin_docusign:1:1', // Date range
           // 'user_id:zen_plugin_docusign::', // Text input
        ),

    ),

);