<?php

$pageName = 'home';

$extension = $this->plugin->id;

$filter_array_default = array(

);

$plugin_links = $this->settings['secondary_links'];

$table = 'zen_plugin_bove_rewards_history';

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
    'member_id',
    'points',
    'note',
);

$thefilters = array(
    'date:zen_plugin_bove_rewards_history:1:1',
    'member_id:zen_plugin_bove_rewards_history:1:1',
    'points:zen_plugin_bove_rewards_history::',
    'note:zen_plugin_bove_rewards_history::',
);

// -----------------------------------------

$admin = new admin;
$gen_table = $admin->get_table($table, $_GET, $defaults, $force_filters, '', $force_headings);

include PP_ADMINPATH . "/cp-includes/base_table.php";