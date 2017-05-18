#!/usr/bin/php -q
<?php
error_reporting(0);

// ---------------------------------------------
// Load Zenbership

require dirname(dirname(dirname(dirname(__FILE__)))) . '/admin/sd-system/config.php';

// ---------------------------------------------
// Load the plugin

$db = new db;
$plugin = new plugin('email_piping');

// ---------------------------------------------
// Load the required components
require_once PP_PATH . '/custom/plugins/email_piping/functions/MailDecode.php';

$local = $plugin->load('LocalParser');
$parser = $plugin->load('MailReader');

$saveDir = PP_PATH . '/admin/sd-system/attachments';

$parser->setSaveDir($saveDir);

$parser->send_email = false;

$saved_files = $parser->readEmail();
$spam = $parser->getSpam();

$subject = $parser->getSubject();
$cc = $parser->getCc();
$body = $parser->getBody();
$to = $local->parseAddress($parser->getTo());
$from = $local->parseAddress($parser->getFrom());
$cc = $local->parseAddress($parser->getCc());
$headers = $parser->getHeaders();

$checkTo = array_merge($to, $cc);

// ---------------------------------------------
$admin = new admin;

$crm = array();
$addTo = array();
$addCc = array();
$exclude = array();

// First we determine whether this email was sent to an employee
// within the Zenbership environment. We do this by matching the
// employee's email with the "to" line of the incoming email.
foreach ($checkTo as $item) {
    $clean = trim($item['email']);
    $employee = $admin->get_employee_by_email($clean);

    if (! empty($employee['id'])) {
        $crm[] = $employee['id'];
    }

    $exclude[] = $clean;
}


// Add into CRM
if (! empty($crm)) {
    $contact = new contact;
    $member = new user;
    $note = new notes;

    $creating = false;
    foreach ($from as $item) {
        $clean = trim($item['email']);

        $checkMem = $member->get_id_from_email($clean);
        if (empty($checkMem)) {
            $checkMem = $contact->find_contact_by_email($clean);
            $checkType = 'contact';
        } else {
            $checkType = 'member';
        }

        // Still no ID found? We need to create a new contact
        // and assign it to the employee is question.
        if (empty($checkMem)) {

            // NEED AN OPTION HERE TO EITHER CREATE
            // A NEW CONTACT OR IGNORE.
            continue;

            /*
            if (!empty($item['name'])) {
                $expName = explode(' ', $item['name']);
                $first_name = array_shift($expName);
                $last_name = implode(' ', $expName);
            } else {
                $first_name = '';
                $last_name = '';
            }

            $contactData = array(
                'email'      => $clean,
                'last_name'  => $last_name,
                'first_name' => $first_name,
            );
            $retu = $contact->create($contactData);
            $checkMem = $retu['id'];
            $checkType = 'contact';

            $contact->assign($checkMem, $crm['0']);
            */
        }


        // Create the note
        $note_type = $note->get_label_from_code('emailin');
        $this_note = array(
            'label' => $note_type,
            'user_id' => $checkMem,
            'item_scope' => $checkType,
            'name' => $subject,
            'note' => $body,
            'added_by' => $crm['0'],
        );
        $noteId = $note->add_note($this_note);

        /*
        $form = new form();
        $form_add = $form->submit_eav_form('pipe', $subject, $checkMem, $this_note, $checkType);
        */

        // Upload files assigned to note
        $uploads = new uploads();
        foreach ($saved_files as $savedFile) {
            $addFileId = $uploads->add_to_db($savedFile['id'], $savedFile['filename'], $savedFile['ext'], $checkMem, $checkType, $savedFile['name'], 'email_attach');
        }

        $plugin->feed('email_received', $checkMem, $checkType, $noteId, $subject, $crm['0'], true);
    }
}