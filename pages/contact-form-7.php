<?php

if (VSPH::isPost($_POST)) {

    update_option("vatansms_contact_form_id", sanitize_text_field($_POST["vatansms_contact_form_id"]));

    update_option("vatansms_contact_form_admin_to_status", sanitize_text_field($_POST["vatansms_contact_form_admin_to_status"]));
    update_option("vatansms_contact_form_admin_to_numbers", sanitize_text_field($_POST["vatansms_contact_form_admin_to_numbers"]));
    update_option("vatansms_contact_form_admin_to_message", sanitize_text_field($_POST["vatansms_contact_form_admin_to_message"]));

    update_option("vatansms_contact_form_user_to_status", sanitize_text_field($_POST["vatansms_contact_form_user_to_status"]));
    update_option("vatansms_contact_form_user_to_message", sanitize_text_field($_POST["vatansms_contact_form_user_to_message"]));

    VSPH::postState("success", "Ayarlar kaydedildi.");
}

if(is_plugin_active('contact-form-7/wp-contact-form-7.php')) {

    $contactForms = get_posts([
        'post_type'     => 'wpcf7_contact_form',
        'numberposts'   => -1
    ]);


    if (get_option("vatansms_contact_form_id") != 0) {
        $selectedForm = WPCF7_ContactForm::get_instance(get_option("vatansms_contact_form_id"));

        $tags = array_filter($selectedForm->scan_form_tags(), function ($tag) {
            if (strlen($tag->name) != 0) {
                $tag->name = sprintf("[%s]", $tag->name); 
                return $tag;
            }
        });
    }
}


?>
<?php include_once (vatansms_get_plugin_path() . "pages/top-bar.php"); ?>
<form action="" method="POST">
    <div class="app-amin box-shadow">
        <div class="app-header">Contact Form 7 Ayarları</div>
        <div class="app-content">

            <div class="cs-secondary" style="margin-bottom: 20px !important;">
                Contact Form 7 eklentisi ile beraber çalışır. Contact Form 7 eklentisini kurmadığınız takdirde çalışmaz. kullandığınız değişkenler forma ait değişkenler olmalıdır.
                örneğin : [adsoyad] şeklinde mesaj metnine yazmalısınız. Formdan gelen değerlerde böyle bir alan var ise bu değişken o değer ile değişecektir.
                Formlardaki telefon inputunun etiketi [telephone] olmalıdır.
            </div>

            <?php if(is_plugin_active('contact-form-7/wp-contact-form-7.php')) { ?>
                <h4 style="margin-bottom: 8px;">Örnek :</h4>
                <img src="<?php echo vatansms_get_plugin_url() ?>assets/orn.png" alt="" style="width: 250px;margin-bottom: 20px; border: 1px solid #909090;">

                <div class="divider"></div>
                <!-- Tamamlandı -->
                <div class="form-group">
                    <label>Ayarların geçerli olacağı formu seçin:</label>
                    <select name="vatansms_contact_form_id" class="cs-form" onchange="jQuery('form').submit()" style="max-width: 100% !important;">
                        <option value="0">Form yok (Pasif)</option>
                        <?php foreach ($contactForms as $form) { ?>
                            <option value="<?php echo $form->ID ?>" <?php echo get_option("vatansms_contact_form_id") == $form->ID ?  "selected" : "" ?>><?php echo esc_html($form->post_title) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <?php if (get_option("vatansms_contact_form_id") != 0) { ?>
                    <div class="form-group" style="margin-top: 20px !important;">
                        <label>Form doldurulduğunda belirlenen numaralara sms gönderilsin mi?</label>

                        <div class="d-flex gap-10">
                            <select class="cs-form" onchange="hideTextarea(this)" name="vatansms_contact_form_admin_to_status">
                                <option value="0" <?php echo (get_option("vatansms_contact_form_admin_to_status") == 0) ? 'selected' : '' ?>>Hayır</option>
                                <option value="1" <?php echo (get_option("vatansms_contact_form_admin_to_status") == 1) ? 'selected' : '' ?>>Evet</option>
                            </select>

                            <div class="d-flex flex-column w-100" style="<?php echo (get_option("vatansms_contact_form_admin_to_status") == 0) ? 'display:none;' : '' ?>">
                                <input type="text" value="<?php echo esc_html(get_option("vatansms_contact_form_admin_to_numbers")) ?>" name="vatansms_contact_form_admin_to_numbers" class="cs-form w-100" placeholder="Belirlenen numaralar">
                                <small class="cs-danger mt-5">Birden fazla numara girecekseniz numaraları virgül(,) ile ayırınız. Örneğin; 5555555555,5555555555,5555555555</small>
                            </div>

                            <div class="w-100" style="<?php echo (get_option("vatansms_contact_form_admin_to_status") == 0) ? 'display:none;' : '' ?>">
								<textarea spellcheck="false" name="vatansms_contact_form_admin_to_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_contact_form_admin_to_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Yeni bir kayıt oluştu. [your-name], [your-email]'; // Replace 'Your default message here' with your actual default text
								?></textarea>

                                <ul class="tags">
                                    <?php foreach ($tags as $tag) { ?>
                                        <li onclick="addTextarea(this)"><?php echo esc_html($tag->name) ?></li>
                                    <?php } ?>
                                </ul>

                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>
                    <!-- Tamamlandı -->

                    <div class="form-group" style="margin-top: 20px !important;">
                        <label>Formu dolduran kişi sms gönderilsin mi?</label>

                        <div class="d-flex gap-10">
                            <select class="cs-form" onchange="hideTextarea(this)" name="vatansms_contact_form_user_to_status">
                                <option value="0" <?php echo (get_option("vatansms_contact_form_user_to_status") == 0) ? 'selected' : '' ?>>Hayır</option>
                                <option value="1" <?php echo (get_option("vatansms_contact_form_user_to_status") == 1) ? 'selected' : '' ?>>Evet</option>
                            </select>

                            <div class="w-100" style="<?php echo (get_option("vatansms_contact_form_user_to_status") == 0) ? 'display:none;' : '' ?>">
								<textarea spellcheck="false" name="vatansms_contact_form_user_to_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_contact_form_user_to_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Sayın [your-name], Kaydınız başarı ile oluşturuldu.'; // Replace 'Your default message here' with your actual default text
								?></textarea>
                                <ul class="tags">
                                    <?php foreach ($tags as $tag) { ?>
                                        <li onclick="addTextarea(this)"><?php echo esc_html($tag->name) ?></li>
                                    <?php } ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                <?php } ?>


                <button class="cs-button mt-10">
                    <i class="far fa-save"></i>
                    Kaydet
                </button>

            <?php } ?>


        </div>
    </div>
</form>



<?php include(vatansms_get_plugin_path() . "components/message.php"); ?>