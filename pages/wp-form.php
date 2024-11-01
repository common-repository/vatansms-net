<?php

if (VSPH::isPost($_POST)) {
    // Save WPForms integration settings
    update_option("vatansms_wpforms_form_id", sanitize_text_field($_POST["vatansms_wpforms_form_id"]));
    update_option("vatansms_wpforms_admin_to_status", sanitize_text_field($_POST["vatansms_wpforms_admin_to_status"]));
    update_option("vatansms_wpforms_admin_to_numbers", sanitize_text_field($_POST["vatansms_wpforms_admin_to_numbers"]));
    update_option("vatansms_wpforms_admin_to_message", sanitize_text_field($_POST["vatansms_wpforms_admin_to_message"]));
    update_option("vatansms_wpforms_user_to_status", sanitize_text_field($_POST["vatansms_wpforms_user_to_status"]));
    update_option("vatansms_wpforms_user_to_message", sanitize_text_field($_POST["vatansms_wpforms_user_to_message"]));
    update_option("vatansms_wpforms_telephone_field_id", sanitize_text_field($_POST["vatansms_wpforms_telephone_field_id"]));

    VSPH::postState("success", "Settings saved successfully.");
}

// Check if WPForms is active
if (is_plugin_active('wpforms-lite/wpforms.php') || is_plugin_active('wpforms/wpforms.php')) {
    // Fetch all WPForms forms
    $forms = wpforms()->form->get();
	
	
		
		if (get_option("vatansms_wpforms_form_id") != 0) {
		$form_id = get_option("vatansms_wpforms_form_id");
		$form = wpforms()->form->get($form_id); // Get the form object by ID
		
		if (!empty($form)) {
			// WPForms stores form data in JSON format in the 'post_content' field
			$form_data = wpforms_decode($form->post_content);
			
			if (isset($form_data['fields']) && !empty($form_data['fields'])) {
				$tags = []; // Initialize an array to hold our tags
				
				foreach ($form_data['fields'] as $field) {
					// Check if the field has a label
					if (!empty($field['label'])) {
						$tag_name = sprintf("[%s]", $field['label']); // Format the label as a tag
						$tags[] = $tag_name; // Add the tag to our array
					}
				}
				
				// Now, $tags array contains all the tags you can use as placeholders
			}
		}
	}
		
	
} else {
    $forms = [];
}

?>
<?php include_once (vatansms_get_plugin_path() . "pages/top-bar.php"); ?>
<form action="" method="POST">
    <div class="app-amin box-shadow">
        <div class="app-header">WPForms Ayarları</div>
        <div class="app-content">
            <!-- Instructions and form selection -->
            <div class="cs-secondary" style="margin-bottom: 20px !important;">
                Bu eklenti WPForms ile birlikte çalışır. WPForms eklentisi kurulu ve etkin değilse çalışmaz. 
				Mesaj metninizde form alanlarına karşılık gelen değişkenleri kullanabilirsiniz, örneğin: [adsoyad]. 
				Eğer formda bu isimde bir alan varsa, bu değişken formdan gelen değer ile değiştirilecektir.
				Telefon numarası için kullanmak istediğiniz alanın ID'sini belirtmeniz gerekmektedir.
            </div>

            <?php if (!empty($forms)) { ?>
                <h4 style="margin-bottom: 8px;">Örnek:</h4>
                <img src="<?php echo vatansms_get_plugin_url() ?>assets/phone-guide.jpg" alt="" style="width: 600px;margin-bottom: 20px; border: 1px solid #909090;">

                <div class="divider"></div>
                
                <!-- Form selection -->
                <div class="form-group">
                    <label>Ayarların geçerli olacağı formu seçin:</label>
                    <select name="vatansms_wpforms_form_id" class="cs-form" onchange="jQuery('form').submit()" style="max-width: 100% !important;">
                        <option value="0">Form yok (Pasif)</option>
                        <?php foreach ($forms as $form) { ?>
                            <option value="<?php echo esc_attr($form->ID); ?>" <?php selected(get_option("vatansms_wpforms_form_id"), $form->ID); ?>><?php echo esc_html($form->post_title); ?></option>
                        <?php } ?>
                    </select>
                </div>
				<div class="form-group" style="margin-top: 20px !important;">
					<label>Telfon Field ID:</label>
					<div class="d-flex flex-column w-100">
						<input type="text" value="<?php echo esc_html(get_option("vatansms_wpforms_telephone_field_id")) ?>" name="vatansms_wpforms_telephone_field_id" class="cs-form w-100" placeholder="Enter the Field ID">
						<small class="cs-danger mt-5">Örneğin: 2. Bu, kullanıcının telefon numarasını içeren alanın ID'sidir.</small>
					</div>
				</div>

				
				<?php if (get_option("vatansms_wpforms_form_id") != 0) { 
				//error_log('Your debug message', 3, WP_CONTENT_DIR . '/my-custom-debug.log');
				?>
                    <div class="form-group" style="margin-top: 20px !important;">
                        <label>Form doldurulduğunda belirlenen numaralara sms gönderilsin mi?</label>

                        <div class="d-flex gap-10">
                            <select class="cs-form" onchange="hideTextarea(this)" name="vatansms_wpforms_admin_to_status">
                                <option value="0" <?php echo (get_option("vatansms_wpforms_admin_to_status") == 0) ? 'selected' : '' ?>>Hayır</option>
                                <option value="1" <?php echo (get_option("vatansms_wpforms_admin_to_status") == 1) ? 'selected' : '' ?>>Evet</option>
                            </select>

                            <div class="d-flex flex-column w-100" style="<?php echo (get_option("vatansms_wpforms_admin_to_status") == 0) ? 'display:none;' : '' ?>">
                                <input type="text" value="<?php echo esc_html(get_option("vatansms_wpforms_admin_to_numbers")) ?>" name="vatansms_wpforms_admin_to_numbers" class="cs-form w-100" placeholder="Belirlenen numaralar">
                                <small class="cs-danger mt-5">Birden fazla numara girecekseniz numaraları virgül(,) ile ayırınız. Örneğin; 5555555555,5555555555,5555555555</small>
                            </div>

                            <div class="w-100" style="<?php echo (get_option("vatansms_wpforms_admin_to_status") == 0) ? 'display:none;' : '' ?>">

							<textarea spellcheck="false" name="vatansms_wpforms_admin_to_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_wpforms_admin_to_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Yeni bir kayıt oluştu. [Name], [E-posta]'; // Replace 'Your default message here' with your actual default text
							?></textarea>

                                <ul class="tags">
                                    <?php foreach ($tags as $tag) { ?>
										<li onclick="addTextarea(this)"><?php echo esc_html($tag); ?></li>
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
                            <select class="cs-form" onchange="hideTextarea(this)" name="vatansms_wpforms_user_to_status">
                                <option value="0" <?php echo (get_option("vatansms_wpforms_user_to_status") == 0) ? 'selected' : '' ?>>Hayır</option>
                                <option value="1" <?php echo (get_option("vatansms_wpforms_user_to_status") == 1) ? 'selected' : '' ?>>Evet</option>
                            </select>

                            <div class="w-100" style="<?php echo (get_option("vatansms_wpforms_user_to_status") == 0) ? 'display:none;' : '' ?>">
								<textarea spellcheck="false" name="vatansms_wpforms_user_to_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_wpforms_user_to_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Sayın [Name], Kaydınız başarı ile oluşturuldu.'; // Replace 'Your default message here' with your actual default text
								?></textarea>
                                <ul class="tags">
                                    <?php foreach ($tags as $tag) { ?>
                                        
										<li onclick="addTextarea(this)"><?php echo esc_html($tag); ?></li>
                                    <?php } ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                <?php } ?>

                <!-- Admin SMS settings -->
                <!-- Similar to Contact Form 7 settings fields -->

                <!-- User SMS settings -->
                <!-- Similar to Contact Form 7 settings fields -->

                <button class="cs-button mt-10">
                    <i class="far fa-save"></i>
                    Kaydet
                </button>
            <?php } else { ?>
                <p>WPForms is not active. Please install and activate WPForms to use this feature.</p>
            <?php } ?>
        </div>
    </div>
	
</form>
<?php include(vatansms_get_plugin_path() . "components/message.php"); ?>