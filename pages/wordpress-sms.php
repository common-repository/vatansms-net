<?php

if (VSPH::isPost($_POST)) {


    // update_option("vatansms_auth_after_user_message_status", sanitize_text_field($_POST["vatansms_auth_after_user_message_status"]));
    // update_option("vatansms_auth_after_user_message", sanitize_text_field($_POST["vatansms_auth_after_user_message"]));

    update_option("vatansms_auth_after_user_message_status_admin", sanitize_text_field($_POST["vatansms_auth_after_user_message_status_admin"]));
    update_option("vatansms_auth_after_user_message_phone_admin", sanitize_text_field($_POST["vatansms_auth_after_user_message_phone_admin"]));
    update_option("vatansms_auth_after_user_message_admin", sanitize_text_field($_POST["vatansms_auth_after_user_message_admin"]));

    VSPH::postState("success", "Ayarlar kaydedildi.");
}



?>
<?php include_once (vatansms_get_plugin_path() . "pages/top-bar.php"); ?>

<form action="" method="POST">
    <div class="app-amin box-shadow">
        <div class="app-header">Wordpress Ayarları</div>
        <div class="app-content">


            <!-- Tamamlandı -->
            <!-- <div class="form-group">
                <label>Yeni bir kayıt oluşturulduğunda kayıt oluşturana sms gönderilsin mi?</label>

                <div class="d-flex gap-10">
                    <select class="cs-form" onchange="hideTextarea(this)" name="vatansms_auth_after_user_message_status">
                        <option value="0" <?php echo (get_option("vatansms_auth_after_user_message_status") == 0) ? 'selected' : '' ?>>Hayır</option>
                        <option value="1" <?php echo (get_option("vatansms_auth_after_user_message_status") == 1) ? 'selected' : '' ?>>Evet</option>
                    </select>

                    <div class="w-100" style="<?php echo (get_option("vatansms_auth_after_user_message_status") == 0) ? 'display:none;' : '' ?>">
                        <textarea spellcheck="false" name="vatansms_auth_after_user_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php echo esc_html(get_option("vatansms_auth_after_user_message")) ?></textarea>
                        <ul class="tags">
                            <li onclick="addTextarea(this)">[ad_soyad]</li>
                            <li onclick="addTextarea(this)">[email]</li>
                        </ul>
                    </div>
                </div>

            </div> -->

            <!-- <div class="divider"></div> -->

            <div class="form-group">
                <label>Yeni bir kayıt oluşturulduğunda yöneticiye sms gönderilsin mi?</label>

                <div class="d-flex gap-10">
                    <select class="cs-form" onchange="hideTextarea(this)" name="vatansms_auth_after_user_message_status_admin">
                        <option value="0" <?php echo (get_option("vatansms_auth_after_user_message_status_admin") == 0) ? 'selected' : '' ?>>Hayır</option>
                        <option value="1" <?php echo (get_option("vatansms_auth_after_user_message_status_admin") == 1) ? 'selected' : '' ?>>Evet</option>
                    </select>

                    <input type="text" class="cs-form w-100" placeholder="Yönetici telefon numarası örn: 5555555555" value="<?php echo esc_html(get_option("vatansms_auth_after_user_message_phone_admin")) ?>" name="vatansms_auth_after_user_message_phone_admin">

                    <div class="w-100" style="<?php echo (get_option("vatansms_auth_after_user_message_status_admin") == 0) ? 'display:none;' : '' ?>">
						<textarea spellcheck="false" name="vatansms_auth_after_user_message_admin" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
							// Retrieve the option value
							$message = get_option("vatansms_auth_after_user_message_admin");
								
							// Check if the message has content, if not set a default message
							echo !empty($message) ? esc_html($message) : 'Yeni bir kayıt oluşturuldu. [kullanici_adi] : [kayit_tarihi] : [email]'; // Replace 'Your default message here' with your actual default text
						?></textarea>
                        <ul class="tags">
                            <li onclick="addTextarea(this)">[kullanici_adi]</li>
                            <li onclick="addTextarea(this)">[kayit_tarihi]</li>
                            <li onclick="addTextarea(this)">[email]</li>
                        </ul>
                    </div>
                </div>

            </div>



            <button class="cs-button mt-10">
                <i class="far fa-save"></i>
                Kaydet
            </button>



        </div>
    </div>
</form>



<?php include(vatansms_get_plugin_path() . "components/message.php"); ?>