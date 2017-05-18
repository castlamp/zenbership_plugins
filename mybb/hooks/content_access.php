<?php

$plugin = new plugin('mybb');
$loaded = $plugin->load('bbsync');

$contentId = $plugin->option('content_id');

$user = new user;

if ($contentId == $data['content_id']) {
    $userData = $user->get_user($data['member_id']);

    $plugin->connectLocal();

    $myBBdata = $loaded->getFromUsername($userData['data']['username']);

    if (empty($myBBdata['uid'])) {
        $uid = $loaded->create(array(
            'username' => $userData['data']['username'],
            'email' => $userData['data']['email'],
        ));
    } else {
        $loaded->changeStatus($myBBdata['uid'], $plugin->option('new_user_group'));
    }

    $plugin->connectZen();
}