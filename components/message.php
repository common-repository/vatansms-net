<?php if (isset($_POST["state"]) && isset($_POST["state_message"])) { ?>
    <div class="alert-<?php echo ($_POST['state'] == 'success') ? 'success' : 'error' ?>">
        <i class="fa fa-<?php echo ($_POST['state'] == 'success') ? 'check' : 'times' ?>" style="margin-right: 10px;"></i>
        <?php echo esc_html($_POST["state_message"]) ?>
    </div>
<?php } ?>