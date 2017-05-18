<?php
$obj->header('add');
?>

<div class="pad24t popupbody">

<fieldset>
    <legend>Overview</legend>
    <div class="pad24t">

    <p>This plugin will trigger DocuSign to send a request to sign to a member or contact in the database. It will track
    whether the user has signed the document and update his/her notes and records accordingly.</p>

    <?php
    echo $this->field->wrap($this->field->string('template_id', '', 'req'), 'DocuSign Template ID', 'You can find you template ID within the DocuSign dashboard.');

    echo $this->field->wrap($this->field->date('due_date', '', 'req'), 'Due Date');
    ?>

    </div>
</fieldset>

<fieldset>
    <legend>Who Are We Sending This To?</legend>
    <div class="pad24t">
    <?php
    echo $this->field->wrap($this->field->radio('user_type', 'member', array('member' => 'Member','contact' => 'Contact')), 'User Type');

    ?>
    <div id="member_options">
    <?php
    echo $this->field->wrap($this->field->memberList('member_id', '', ''), 'Member');
    ?>
    </div>
    <div id="contact_options" style="display:none;">
    <?php
    echo $this->field->wrap($this->field->contactList('contact_id', '', ''), 'Contact');
    ?>
    </div>

    </div>
</fieldset>

<script>
    $(document).ready(function() {
        $('input[type=radio][name=user_type]').change(function() {
            if (this.value == 'member') {
                swap_div('member_options', 'contact_options');
            }
            else if (this.value == 'contact') {
                swap_div('contact_options', 'member_options');
            }
        });
    });
</script>

</div>

<?php
$obj->footer('add');