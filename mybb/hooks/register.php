<?php

$plugin = new plugin('mybb');
$loaded = $plugin->load('bbsync');

$contentId = $plugin->option('content_id');

foreach ($data['content'] as $item) {

    if ($item['id'] == $contentId) {
        $plugin->connectLocal();

        $create = $loaded->create(array(
            'username' => $data['data']['username'],
            'email' => $data['data']['email'],
        ));

        $plugin->connectZen();
        break;
    }

}
