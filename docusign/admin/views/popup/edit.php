<?php
$get = $this->extensionObj->get($_POST['id']);

$obj->header('edit');
?>

    <fieldset>
        <legend>Basic Details</legend>
        <div class="pad24t">

            <dl>
                <dt>Status</dt>
                <dd><?php echo $get['status']; ?></dd>
                <dt>Date Sent</dt>
                <dd><?php echo format_date($get['date']); ?></dd>
                <dt>Template ID</dt>
                <dd><?php echo $get['template_id']; ?></dd>
                <dt>Envelop ID</dt>
                <dd><?php echo $get['envelop_id']; ?></dd>
                <dt>Envelop URL</dt>
                <dd><?php echo $get['url']; ?></dd>
            </dl>
            <div class="clear"></div>

		</div>
	</fieldset>

    <fieldset>
        <legend>Signature Details</legend>
        <div class="pad24t">
            <dl>
            <?php
            if ($get['confirmed'] == '1') {
                ?>
                <dt>Date Signed</dt>
                <dd><?php echo format_date($get['date_confirmed']); ?></dd>
                <dt>Signed Files</dt>
                <dd><?php
                $folder = PP_PATH . '/custom/uploads/' . $get['storage_folder'];
                $url = PP_URL . '/custom/uploads/' . $get['storage_folder'];
                $files = scandir($folder);
                foreach ($files as $aFile) {
                    if ($aFile == '.' || $aFile == '..') continue;

                    echo "<a href=\"$url/$aFile\" target=\"_blank\">" . $aFile . "</a><br />";
                }
                ?></dd>
            <?php
            } else {
            ?>
            <p>Not yet signed.</p>
            <?php
            }
            ?>
            </dl>
            <div class="clear"></div>
        </div>
    </fieldset>


<?php
$obj->footer('edit');