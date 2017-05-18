<?php

if (! empty($input['id'])) {
    $q1 = $this->delete("
        DELETE FROM `zen_plugin_docusign`
        WHERE `id`='" . $this->mysql_clean($input['id']) . "'
        LIMIT 1
    ");
}