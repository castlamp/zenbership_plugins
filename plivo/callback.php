<?php

// Handles when someone replies or texts your Plivo number.
// https://www.plivo.com/docs/getting-started/forward-an-incoming-sms/

$msg = 'Received on ' . date('Y-m-d H:i:s') . "\n";
$msg .= 'From: ' . $_POST['From'] . "\n";
$msg .= 'To: ' . $_POST['To'] . "\n";
$msg .= 'Msg: ' . $_POST['Text'] . "\n";
$msg .= '================================' . "\n";

file_put_contents('logs/incomingTextLog.txt', $msg, FILE_APPEND);

echo $msg;
exit;