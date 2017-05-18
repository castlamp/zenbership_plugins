<?php

return array(

    // -----------------------------------
    // General information
    'permission' => 'docusign',

    'menu' => 'DocuSign',

    // -----------------------------------
    // Secondary links on filters menu.
    'secondary_links' => array(
        array(
            'type' => 'popup',
            'act' => 'add',
            'name' => 'Create Signing Request',
        ),
        array(
            'type' => 'popup',
            'act' => 'triggers',
            'name' => 'Triggers',
        ),
    ),


    // -----------------------------------
    // Tables
    'zen_plugin_docusign' => array(

        'force_headings' => array(
            'date',
            'user_id',
            'status',
            'date_confirmed',
            'template_id',
            'url',
        ),

        'order' => 'date',

        'dir' => 'DESC',

        'display' => '50',

        'force_filters' => array(

        ),

        'filters' => array(
            'date:zen_plugin_docusign:1:1',
            'date_confirmed:zen_plugin_docusign:1:1',
            'user_id:zen_plugin_docusign::',
            'status:zen_plugin_docusign::',
            'envelop_id:zen_plugin_docusign::',
            'template_id:zen_plugin_docusign::',
            'url:zen_plugin_docusign::',
        ),

    ),

);