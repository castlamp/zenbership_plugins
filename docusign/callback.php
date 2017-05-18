<?php

/**
 * Accepts a webhook request from Docusign and updates the database
 * to confirm signing. Also stores the signed documents.
 *
 * Zenbership Membership Software
 * Copyright (C) 2013-2016 Castlamp, LLC
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Castlamp
 * @link        http://www.castlamp.com/
 * @link        http://www.zenbership.com/
 * @copyright   (c) 2013-2016 Castlamp
 * @license     http://www.gnu.org/licenses/gpl-3.0.en.html
 * @project     Zenbership Membership Software
 */

// Load Zenbership
require dirname(dirname(dirname(dirname(__FILE__)))) . '/admin/sd-system/config.php';

$db = new db;
$plugin = new plugin('docusign');

$data = file_get_contents('php://input');

$data = file_get_contents('../../uploads/xml.xml');

$xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_PARSEHUGE);

$status = (string)$xml->EnvelopeStatus->Status;
$status = strtolower($status);

if ($status == "completed" || $status == "declined" || $status === "voided") {

    // Store the key items
    $envelope_id = (string)$xml->EnvelopeStatus->EnvelopeID;
    $time_generated = (string)$xml->EnvelopeStatus->TimeGenerated;

    $task_id = $db->start_task('docusign_confirmed', 'system', $envelope_id);

    // Save the signed documents.
    $files_dir = PP_PATH . '/custom/uploads/';
    $envelop_dir = 'docusign/E' . $envelope_id;

    if (! is_dir($files_dir . 'docusign')) {
        mkdir($files_dir . 'docusign', 0755);
    }

    if (! is_dir($files_dir . $envelop_dir)) {
        mkdir($files_dir . $envelop_dir, 0755);
    }

    $filename = $files_dir . $envelop_dir . '/' . str_replace(':' , '_' , $time_generated) . ".xml";

    if (! file_put_contents($filename, $data)) {
        $db->end_task($task_id, '0', 'Could not write file.');

        echo "Exit: could not write files.";
        exit;
    }

    // Get the Zenbership local data
    $ext = $plugin->getExtension();
    $zenData = $ext->getFromEnvelopId($envelope_id);

    // Save the documents
    $uploads = new uploads();

    foreach ($xml->DocumentPDFs->DocumentPDF as $pdf) {
        $save_name = (string)$pdf->Name;

        $gen_name = generate_id();
        $innerPath = $envelop_dir . '/' . $gen_name . '.pdf';

        file_put_contents($files_dir . $innerPath, base64_decode((string)$pdf->PDFBytes));

        $uploads->add_to_db($gen_name, $save_name, 'pdf', $zenData['user_id'], $zenData['user_type'], $innerPath, 'docusign', '', true);
    }

    $update = $db->update("
        UPDATE `zen_plugin_docusign`
        SET
            `status`='" . $db->mysql_clean($status) . "',
            `confirmed`='1',
            `date_confirmed`='" . current_date() . "',
            `storage_folder`='" . $envelop_dir . "'
        WHERE
            `envelop_id`='" . $db->mysql_clean($envelope_id) . "'
        LIMIT 1
    ");

    $db->end_task($task_id, '1');

    $note = 'Document completed by %user_link%. Status: ' . $status;

    $plugin->feed('confirmed', $zenData['user_id'], $zenData['user_type'], $zenData['id'], $note);
}