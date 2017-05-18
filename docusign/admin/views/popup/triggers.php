<?php
$obj->header('add');
?>

<script src="js/form_rotator.js" type="text/javascript"></script>

<ul id="theStepList">
    <li class="on" onclick="return goToStep('0');">Existing</li>
    <li onclick="return goToStep('1');">Create New</li>
</ul>


<div class="popupbody">

<ul id="formlist" class="on">

<li class="form_step">

    <?php
    $triggers = $this->extensionObj->getTriggers();
    if (empty($triggers)) {
        echo "<p class='weak'>No triggers have been setup yet!</p>";
    } else {
        ?>

        <table class="tablesorter listings popuptable">
            <thead>
            <tr>
                <th>Trigger</th>
                <th>Specific</th>
                <th>Template ID</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($triggers as $one) {
                ?>
                <tr id="td-cell-<?php echo $one['id']; ?>">
                    <td><?php echo $one['trigger']; ?></td>
                    <td><?php echo $one['trigger_id']; ?></td>
                    <td><?php echo $one['template_id']; ?></td>
                    <td><a href="null.php" onclick="return json_add('custom:docusign:delete_trigger', '<?php echo $one['id']; ?>', '', 'skip');">Delete</a></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
        <div class="clear"></div>

    <?php
    }
    ?>


</li>
<li class="form_step pad24t">

    <fieldset>
        <legend>Create a Trigger</legend>
        <div class="pad24t">

            <?php

            echo $this->field->wrap($this->field->string('template_id', '', 'req'), 'DocuSign Template ID', '');

            $trigs = array(
                'member_create' => 'Member Created',
                'contact_create' => 'Contact Created',
                'event_add_registrant' => 'Event Registration',
                'form' => 'Specific Form Submission',
                'transaction' => 'Product Purchase',
            );

            echo $this->field->wrap($this->field->radio('trigger_type', 'member', $trigs), 'Trigger Type', '');

            ?>

            <div id="ds_forms" style="display:none;">
                <?php
                echo $this->field->wrap($this->field->formList('form_id', '', ''), 'Form');
                ?>
            </div>

            <div id="ds_products" style="display:none;">
                <?php
                echo $this->field->wrap($this->field->productList('product_id', '', ''), 'Product');
                ?>
            </div>

        </div>
    </fieldset>

</li>
</ul>

<script>
    $(document).ready(function() {
        $('input[type=radio][name=trigger_type]').change(function() {
            if (this.value == 'form') {
                swap_div('ds_forms', 'ds_products');
            }
            else if (this.value == 'transaction') {
                swap_div('ds_products', 'ds_forms');
            }
            else {
                hide_div('ds_forms');
                hide_div('ds_products');
            }
        });
    });
</script>

</div>

<?php
$obj->footer('add');