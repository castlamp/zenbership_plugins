<?php

$plugin = new plugin('member_directory');
$list = $plugin->load('listings');
$results = $list->getResults();
$template = $list->renderOutput();

echo $template;
exit;