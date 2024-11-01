<?php

if (VSPH::isPost($_POST)) {

    update_option("vatansms_wc_create_order_to_customer_status", sanitize_text_field($_POST["vatansms_wc_create_order_to_customer_status"]));
    update_option("vatansms_wc_create_order_to_customer_message", sanitize_text_field($_POST["vatansms_wc_create_order_to_customer_message"]));

    update_option("vatansms_wc_create_order_to_numbers_status", sanitize_text_field($_POST["vatansms_wc_create_order_to_numbers_status"]));
    update_option("vatansms_wc_create_order_to_numbers", sanitize_text_field($_POST["vatansms_wc_create_order_to_numbers"]));
    update_option("vatansms_wc_create_order_to_numbers_message", sanitize_text_field($_POST["vatansms_wc_create_order_to_numbers_message"]));

    update_option("vatansms_wc_cancel_order_to_status", sanitize_text_field($_POST["vatansms_wc_cancel_order_to_status"]));
    update_option("vatansms_wc_cancel_order_to_message", sanitize_text_field($_POST["vatansms_wc_cancel_order_to_message"]));

    update_option("vatansms_wc_complete_order_to_status", sanitize_text_field($_POST["vatansms_wc_complete_order_to_status"]));
    update_option("vatansms_wc_complete_order_to_message", sanitize_text_field($_POST["vatansms_wc_complete_order_to_message"]));

    update_option("vatansms_wc_prepare_order_to_status", sanitize_text_field($_POST["vatansms_wc_prepare_order_to_status"]));
    update_option("vatansms_wc_prepare_order_to_message", sanitize_text_field($_POST["vatansms_wc_prepare_order_to_message"]));

    update_option("vatansms_wc_on_hold_order_to_status", sanitize_text_field($_POST["vatansms_wc_on_hold_order_to_status"]));
    update_option("vatansms_wc_on_hold_order_to_message", sanitize_text_field($_POST["vatansms_wc_on_hold_order_to_message"]));

    VSPH::postState("success", "Woocommerce sms ayarları kaydedildi.");
}

?>
<?php include_once (vatansms_get_plugin_path() . "pages/top-bar.php"); ?>
		<form action="" method="POST">
			<div class="app-amin box-shadow">
				<div class="app-header">Woocommerce</div>
				<div class="app-content">
					<!-- Tamamlandı -->
					<div class="form-group">
						<label>Yeni bir sipariş oluşturulduğunda müşteriye sms gönderilsin mi?</label>

						<div class="d-flex gap-10">
							<select class="cs-form" onchange="hideTextarea(this)" name="vatansms_wc_create_order_to_customer_status">
								<option value="0" <?php echo (get_option("vatansms_wc_create_order_to_customer_status") == 0) ? 'selected' : '' ?>>Hayır</option>
								<option value="1" <?php echo (get_option("vatansms_wc_create_order_to_customer_status") == 1) ? 'selected' : '' ?>>Evet</option>
							</select>

							<div class="w-100" style="<?php echo (get_option("vatansms_wc_create_order_to_customer_status") == 0) ? 'display:none;' : '' ?>">
								<textarea spellcheck="false" name="vatansms_wc_create_order_to_customer_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_wc_create_order_to_customer_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Merhaba [ad_soyad], Yeni bir sipariş olusturdunuz -siparis no:[siparis_no]'; // Replace 'Your default message here' with your actual default text
								?></textarea>
								<?php include(vatansms_get_plugin_path() . "components/tags.php"); ?>
							</div>
						</div>
					</div>

					<div class="divider"></div>
					<!-- Tamamlandı -->
					<div class="form-group">
						<label>Yeni bir sipariş oluşturulduğunda belirlenen numaralara sms gönderilsin mi?</label>

						<div class="d-flex gap-10">
							<select class="cs-form" onchange="hideTextarea(this)" name="vatansms_wc_create_order_to_numbers_status">
								<option value="0" <?php echo (get_option("vatansms_wc_create_order_to_numbers_status") == 0) ? 'selected' : '' ?>>Hayır</option>
								<option value="1" <?php echo (get_option("vatansms_wc_create_order_to_numbers_status") == 1) ? 'selected' : '' ?>>Evet</option>
							</select>

							<div class="d-flex flex-column w-100" style="<?php echo (get_option("vatansms_wc_create_order_to_numbers_status") == 0) ? 'display:none;' : '' ?>">
								<input type="text" value="<?php echo get_option("vatansms_wc_create_order_to_numbers") ?>" name="vatansms_wc_create_order_to_numbers" class="cs-form w-100" placeholder="Belirlenen numaralar">
								<small class="cs-danger mt-5">Birden fazla numara girecekseniz numaraları virgül(,) ile ayırınız. Örneğin; 5555555555,5555555555,5555555555</small>
							</div>
							<div class="w-100" style="<?php echo (get_option("vatansms_wc_create_order_to_numbers_status") == 0) ? 'display:none;' : '' ?>">
								<textarea spellcheck="false" name="vatansms_wc_create_order_to_numbers_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_wc_create_order_to_numbers_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Yeni bir sipariş oluşturuldu, Siparişi kontrol ediniz. sipariş_no:[siparis_no]'; // Replace 'Your default message here' with your actual default text
								?></textarea>
								<?php include(vatansms_get_plugin_path() . "components/tags.php"); ?>
							</div>
						</div>
					</div>

					<div class="divider"></div>
					<!-- Tamamlandı -->
					<div class="form-group">
						<label>Siparişin durumu "<b>İptal edildi</b>" olarak değiştirildiğinde müşteriye sms gönderilsin mi?</label>

						<div class="d-flex gap-10">
							<select class="cs-form" onchange="hideTextarea(this)" name="vatansms_wc_cancel_order_to_status">
								<option value="0" <?php echo (get_option("vatansms_wc_cancel_order_to_status") == 0) ? 'selected' : '' ?>>Hayır</option>
								<option value="1" <?php echo (get_option("vatansms_wc_cancel_order_to_status") == 1) ? 'selected' : '' ?>>Evet</option>
							</select>

							<div class="w-100" style="<?php echo (get_option("vatansms_wc_cancel_order_to_status") == 0) ? 'display:none;' : '' ?>">
								<textarea spellcheck="false" name="vatansms_wc_cancel_order_to_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_wc_cancel_order_to_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Merhaba [ad_soyad], Siparişiniz [siparis_no] , İptal edildi'; // Replace 'Your default message here' with your actual default text
								?></textarea>
								<?php include(vatansms_get_plugin_path() . "components/tags.php"); ?>
							</div>
						</div>
					</div>


					<div class="divider"></div>

					<div class="form-group">
						<label>Siparişin durumu "<b>Hazırlanıyor</b>" olarak değiştirildiğinde müşteriye sms gönderilsin mi?</label>

						<div class="d-flex gap-10">
							<select class="cs-form" onchange="hideTextarea(this)" name="vatansms_wc_prepare_order_to_status">
								<option value="0" <?php echo (get_option("vatansms_wc_prepare_order_to_status") == 0) ? 'selected' : '' ?>>Hayır</option>
								<option value="1" <?php echo (get_option("vatansms_wc_prepare_order_to_status") == 1) ? 'selected' : '' ?>>Evet</option>
							</select>

							<div class="w-100" style="<?php echo (get_option("vatansms_wc_prepare_order_to_status") == 0) ? 'display:none;' : '' ?>">
								<textarea spellcheck="false" name="vatansms_wc_prepare_order_to_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_wc_prepare_order_to_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Merhaba [ad_soyad], Siparişiniz [siparis_no] , Hazırlanıyor.'; // Replace 'Your default message here' with your actual default text
								?></textarea>
								<?php include(vatansms_get_plugin_path() . "components/tags.php"); ?>
							</div>
						</div>
					</div>



					<div class="divider"></div>

					<div class="form-group">
						<label>Siparişin durumu "<b>Ödeme Bekleniyor</b>" olarak değiştirildiğinde müşteriye sms gönderilsin mi?</label>

						<div class="d-flex gap-10">
							<select class="cs-form" onchange="hideTextarea(this)" name="vatansms_wc_on_hold_order_to_status">
								<option value="0" <?php echo (get_option("vatansms_wc_on_hold_order_to_status") == 0) ? 'selected' : '' ?>>Hayır</option>
								<option value="1" <?php echo (get_option("vatansms_wc_on_hold_order_to_status") == 1) ? 'selected' : '' ?>>Evet</option>
							</select>

							<div class="w-100" style="<?php echo (get_option("vatansms_wc_on_hold_order_to_status") == 0) ? 'display:none;' : '' ?>">
								<textarea spellcheck="false" name="vatansms_wc_on_hold_order_to_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_wc_on_hold_order_to_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Merhaba [ad_soyad], Siparişiniz [siparis_no] , Ödeme Bekleniyor.'; // Replace 'Your default message here' with your actual default text
								?></textarea>
								<?php include(vatansms_get_plugin_path() . "components/tags.php"); ?>
							</div>
						</div>
					</div>

					<div class="divider"></div>
					<!-- Tamamlandı -->
					<div class="form-group">
						<label>Siparişin durumu "<b>Tamamlandı</b>" olarak değiştirildiğinde müşteriye sms gönderilsin mi?</label>

						<div class="d-flex gap-10">
							<select class="cs-form" onchange="hideTextarea(this)" name="vatansms_wc_complete_order_to_status">
								<option value="0" <?php echo (get_option("vatansms_wc_complete_order_to_status") == 0) ? 'selected' : '' ?>>Hayır</option>
								<option value="1" <?php echo (get_option("vatansms_wc_complete_order_to_status") == 1) ? 'selected' : '' ?>>Evet</option>
							</select>

							<div class="w-100" style="<?php echo (get_option("vatansms_wc_complete_order_to_status") == 0) ? 'display:none;' : '' ?>">
								<textarea spellcheck="false" name="vatansms_wc_complete_order_to_message" class="cs-form w-100" placeholder="Mesajınızı giriniz" style="height: 110px !important;"><?php 
								// Retrieve the option value
								$message = get_option("vatansms_wc_complete_order_to_message");
								
								// Check if the message has content, if not set a default message
								echo !empty($message) ? esc_html($message) : 'Merhaba [ad_soyad], Siparişiniz [siparis_no] , Tamamlandı.'; // Replace 'Your default message here' with your actual default text
								?></textarea>
								<?php include(vatansms_get_plugin_path() . "components/tags.php"); ?>
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