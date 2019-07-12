<?php if (!$ajax && !$popup && !$as_module) { ?>
<?php $simple_page = 'simpleedit'; include $simple_header; ?>
<? include 'includes/mainmenu.php';?>
<div class="mob_bread">
    <a href="/"><?php echo $heading_title; ?></a>
</div>
<div class="main_content m_row">
    <div class="account_title">
        Здравствуйте, <?=$user['firstname']?> <?=$user['lastname']?>
    </div>

    <? include 'includes/leftmenu_account.php';?>
    <div class="content" id="content">
        <div class="account_title_content">
            <?
            if($_GET['flag']=='pod')
            {
            echo 'Мои подписки';
            $class_form='pod';
            }
            else
            {
            echo 'Моя информация';
            $class_form='';
            }

?>

        </div>

        <?
        //print_r($_POST);
      if($success) {

    ?>
        <div class="change_ok">
            Изменения сохранены
        </div>
        <? } ?>


        <div class="account_content">


        <div class="simple-content">
            <?php } ?>
            <?php if (!$ajax || ($ajax && $popup)) { ?>
            <script type="text/javascript">
                var startSimpleInterval = window.setInterval(function(){
                    if (typeof jQuery !== 'undefined' && typeof Simplepage === "function" && jQuery.isReady) {
                        window.clearInterval(startSimpleInterval);

                        var simplepage = new Simplepage({
                            additionalParams: "<?php echo $additional_params ?>",
                            additionalPath: "<?php echo $additional_path ?>",
                            mainUrl: "<?php echo $action; ?>",
                            mainContainer: "#simplepage_form",
                            scrollToError: <?php echo $scroll_to_error ? 1 : 0 ?>,
                        notificationDefault: <?php echo $notification_default ? 1 : 0 ?>,
                        notificationToasts: <?php echo $notification_toasts ? 1 : 0 ?>,
                        notificationCheckForm: <?php echo $notification_check_form ? 1 : 0 ?>,
                        notificationCheckFormText: "<?php echo $notification_check_form_text ?>",
                            languageCode: "<?php echo $language_code ?>",
                            javascriptCallback: function() {<?php echo $javascript_callback ?>}
                    });

                        if (typeof toastr !== 'undefined') {
                            toastr.options.positionClass = "<?php echo $notification_position ? $notification_position : 'toast-top-right' ?>";
                            toastr.options.timeOut = "<?php echo $notification_timeout ? $notification_timeout : '5000' ?>";
                            toastr.options.progressBar = true;
                        }

                        simplepage.init();
                    }
                },0);
            </script>
            <?php } ?>
            <div class="user_info_form <?=$class_form?>">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="simplepage_form">
                <div class="simpleregister" id="simpleedit">
                    <?php if ($error_warning) { ?>
                    <div class="warning alert alert-danger"><?php echo $error_warning; ?></div>
                    <?php } ?>

                        <?php foreach ($rows as $row) { ?>
                        <?php echo $row ?>
                        <?php } ?>
                    <div class="user_info_form_btn">
                     <a class="btn_blue" data-onclick="submit" id="simpleregister_button_confirm">Сохранить изменения</a>
                    </div>
                </div>
                <?php if ($redirect) { ?>
                    <input type="hidden" id="simple_redirect_url" value="<?php echo $action; ?>">

                <?php } ?>
                <input type="hidden" name="pod" value="1">
            </form>
            </div>
            <?php if (!$ajax && !$popup && !$as_module) { ?>
        </div>

        </div>
    </div>


    <div class="clear"></div>
</div>



<?php include $simple_footer ?>
<?php } ?>