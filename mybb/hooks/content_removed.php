<?php

$plugin = new plugin('mybb');
$loaded = $plugin->load('bbsync');

$contentId = $plugin->option('content_id');

$user = new user;

if ($contentId == $data['data']['final_content']['content_id']) {
    $username = $user->get_username($data['data']['final_content']['member_id']);

    $plugin->connectLocal();

    $myBBdata = $loaded->getFromUsername($username);

    if (empty($myBBdata['uid'])) {
        $loaded->changeStatus($myBBdata['uid'], '1');
    }

    $plugin->connectZen();
}