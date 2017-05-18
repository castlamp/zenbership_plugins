<?php

$extension = $this->plugin->id;

$table = 'zen_plugin_myTableName';

$pageName = 'home';

// -----------------------------------------

$filter_array_default = array(

);

$plugin_links = $this->settings['secondary_links'];

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

);

$thefilters = array(

);

// -----------------------------------------

$admin = new admin;
$gen_table = $admin->get_table($table, $_GET, $defaults, $force_filters, '', $force_headings);

include PP_ADMINPATH . "/cp-includes/base_table.php";