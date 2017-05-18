<?php

$plugin = new plugin('member_directory');
$list = $plugin->load('profile');
$results = $list->getProfile();
$template = $list->renderOutput();

echo $template;
exit;