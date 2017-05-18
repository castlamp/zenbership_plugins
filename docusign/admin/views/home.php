<?php
$pageName = 'home';

$extension = $this->plugin->id;

$filter_array_default = array(

);

$plugin_links = $this->settings['secondary_links'];

$table = 'zen_plugin_docusign';

$order = $table . '.date';

$dir = 'DESC';

$display = '50';

$page = '1';

$defaults = array(
    'sort'    => $order,
    'order'   => $dir,
    'page'    => $page,
    'display' => $display,
    'filters' => $filter_array_default,
);

$force_filters = array();

$force_headings = array(
    'date',
    'user_id',
    'status',
    'date_confirmed',
    'template_id',
    'url',
);

$thefilters = array(
    'date:zen_plugin_docusign:1:1',
    'date_confirmed:zen_plugin_docusign:1:1',
    'user_id:zen_plugin_docusign::',
    'status:zen_plugin_docusign::',
    'envelop_id:zen_plugin_docusign::',
    'template_id:zen_plugin_docusign::',
    'url:zen_plugin_docusign::',
);

// -----------------------------------------

$admin = new admin;
$gen_table = $admin->get_table($table, $_GET, $defaults, $force_filters, '', $force_headings);

include PP_ADMINPATH . "/cp-includes/base_table.php";