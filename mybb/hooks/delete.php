<?php

$plugin = new plugin('mybb');
$loaded = $plugin->load('bbsync');

$plugin->connectLocal();

$myBBdata = $loaded->getFromUsername($data['data']['final_content']['username']);

if (empty($myBBdata['uid'])) {
    $loaded->delete($myBBdata['uid']);
}

$plugin->connectZen();