<?php

$plugin = new plugin('mybb');
$loaded = $plugin->load('bbsync');

$plugin->connectLocal();

$loaded->killSession();

$plugin->connectZen();