<?php

$plugin = new plugin('mybb');
$loaded = $plugin->load('bbsync');

$contentId = $plugin->option('content_id');

if (! empty($contentId)) {
    $user = new user;
    $access = $user->check_content_access_id($contentId, $data['member_id']);
    if (! empty($access)) {
        $proceed = true;
    } else {
        $proceed = false;
    }
} else {
    $proceed = true;
}

if ($proceed) {
    $plugin->connectLocal();

    $myBBdata = $loaded->getFromUsername($data['member']['data']['username']);

    if (empty($myBBdata['uid'])) {
        $uid = $loaded->create(array(
            'username' => $data['member']['data']['username'],
            'email' => $data['member']['data']['email'],
        ));
    } else {
        $uid = $myBBdata['uid'];
    }

    $session = $loaded->startSession($uid);

    $plugin->connectZen();
}
