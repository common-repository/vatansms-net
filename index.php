<?php
/*
Plugin Name: VatanSMS.NET
Description: Woocommerce ve WPForm ve Contact Form 7 ile uyumlu çalışan www.vatansms.net firmasının sms gönderim eklentisidir. Bu eklenti sayesinde woocommerce siparişleriniz sonrasında sms gönderimini yönetebilir, wordpress üyelerinize, woocommerce müşterilerinize toplu olarak sms atabilirsiniz.
Version: 2.6
Author: VatanSMS
Author URI: https://www.vatansms.net/
License: GNU
*/


require(vatansms_get_plugin_path() . "helpers/helper.php");
require(vatansms_get_plugin_path() . "helpers/sms-helper.php");


function vatansms_get_plugin_url()
{
	return plugin_dir_url(__FILE__);
}

function vatansms_get_plugin_path()
{
	return plugin_dir_path(__FILE__);
}

function vatansms_get_customers_phones()
{
	global $wpdb;

	$results = $wpdb->get_col("
        SELECT DISTINCT um.meta_value FROM {$wpdb->prefix}users as u
        INNER JOIN {$wpdb->prefix}usermeta as um ON um.user_id = u.ID
        WHERE um.meta_key LIKE 'billing_phone' AND um.meta_value != ''
    ");

	return implode(',', $results);
}


add_action("admin_init", function () {

	add_option("vatansms_api_id");
	add_option("vatansms_api_key");
	add_option("vatansms_is_login");
	add_option("vatansms_sender");
	add_option("vatansms_fullname");
	add_option("vatansms_kredit");

	// Yeni bir sipariş oluşturulduğunda müşteriye sms gönderilsin mi?
	add_option("vatansms_wc_create_order_to_customer_status");
	add_option("vatansms_wc_create_order_to_customer_message");

	// Yeni bir sipariş oluşturulduğunda belirlenen numaralara sms gönderilsin mi?
	add_option("vatansms_wc_create_order_to_numbers_status");
	add_option("vatansms_wc_create_order_to_numbers");
	add_option("vatansms_wc_create_order_to_numbers_message");

	// Siparişin durumu "İptal edildi" olarak değiştirildiğinde müşteriye sms gönderilsin mi?
	add_option("vatansms_wc_cancel_order_to_status");
	add_option("vatansms_wc_cancel_order_to_message");

	// Siparişin durumu "Tamamlandı" olarak değiştirildiğinde müşteriye sms gönderilsin mi?
	add_option("vatansms_wc_complete_order_to_status");
	add_option("vatansms_wc_complete_order_to_message");

	// Siparişin durumu "Hazırlanıyor" olarak değiştirildiğinde müşteriye sms gönderilsin mi?
	add_option("vatansms_wc_prepare_order_to_status");
	add_option("vatansms_wc_prepare_order_to_message");

	// Siparişin durumu "Ödeme Bekleniyor" olarak değiştirildiğinde müşteriye sms gönderilsin mi?
	add_option("vatansms_wc_on_hold_order_to_status");
	add_option("vatansms_wc_on_hold_order_to_message");

	// Contact form seçili form
	add_option("vatansms_contact_form_id", 0);
	
	
	// Wordpress Üyelik SMS
	add_option("vatansms_auth_after_user_message_status", 0);
	add_option("vatansms_auth_after_user_message");
	
	add_option("vatansms_auth_after_user_message_status_admin", 0);
	add_option("vatansms_auth_after_user_message_phone_admin");
	add_option("vatansms_auth_after_user_message_admin");
	

	// Contact form admine sms
	add_option("vatansms_contact_form_admin_to_status");
	add_option("vatansms_contact_form_admin_to_numbers");
	add_option("vatansms_contact_form_admin_to_message");

	// Concact formda kullanıcıya sms
	add_option("vatansms_contact_form_user_to_status");
	add_option("vatansms_contact_form_user_to_message");
	
	
	
	
	// WPForm Contact form seçili form
	add_option("vatansms_wpforms_form_id", 0);
	add_option("vatansms_wpforms_telephone_field_id");
	// WPForm Contact form admine sms
	add_option("vatansms_wpforms_admin_to_status");
	add_option("vatansms_wpforms_admin_to_numbers");
	add_option("vatansms_wpforms_admin_to_message");

	// WPForm Concact formda kullanıcıya sms
	add_option("vatansms_wpforms_user_to_status");
	add_option("vatansms_wpforms_user_to_message");
});


add_action("admin_menu", function () {
	$title = "VatanSMS";

	add_menu_page(
		"Giriş Yap - $title",
		"VatanSMS",
		"manage_options",
		"vatansms",
		function () {
			require_once("pages/login.php");
		},
		plugins_url("assets/logo.png", __FILE__),
		null
	);

	add_submenu_page(
		"vatansms",
		"Giriş Yap - $title",
		"Giriş Yap",
		"manage_options",
		"vatansms",
		function () {
			require_once("pages/login.php");
		}
	);
	

	add_submenu_page(
		"vatansms",
		"Wordpress Ayarları - $title",
		"Wordpress",
		"manage_options",
		"vatansms-wordpress-sms",
		function () {
			require_once("pages/wordpress-sms.php");
		}
	);
	

	add_submenu_page(
		"vatansms",
		"SMS Gönder - $title",
		"SMS Gönder",
		"manage_options",
		"vatansms-sms-send",
		function () {
			require_once("pages/sms-send.php");
		}
	);

	add_submenu_page(
		"vatansms",
		"SMS Gönder (Toplu) - $title",
		"SMS Gönder (Toplu)",
		"manage_options",
		"vatansms-sms-send-all",
		function () {
			require_once("pages/sms-send-all.php");
		}
	);

	
	add_submenu_page(
		"vatansms",
		"Woocommerce Ayarları - $title",
		"Woocommerce",
		"manage_options",
		"vatansms-woocommerce",
		function () {
			require_once("pages/woocommerce.php");
		}
	);


	add_submenu_page(
		"vatansms",
		"Contact Form 7 Ayarları - $title",
		"Contact Form 7",
		"manage_options",
		"vatansms-contact-form-7",
		function () {
			require_once("pages/contact-form-7.php");
		}
	);
	
	add_submenu_page(
		"vatansms",
		"WP Form Ayarları - $title",
		"WP Form",
		"manage_options",
		"vatansms-wp-form",
		function () {
			require_once("pages/wp-form.php");
		}
	);
	
	add_submenu_page(
		"vatansms",
		"SMS Raporları - $title",
		"SMS Raporları",
		"manage_options",
		"vatansms-sms-reports",
		function () {
			require_once("pages/sms-reports.php");
		}
	);
	
	
});


add_action("admin_enqueue_scripts", function ($hook) {
	$pages = [
		"toplevel_page_vatansms",
		"vatansms_page_vatansms-woocommerce",
		"vatansms_page_vatansms-contact-form-7",
		"vatansms_page_vatansms-wp-form",
		"vatansms_page_vatansms-sms-send",
		"vatansms_page_vatansms-sms-send-all",
		"vatansms_page_vatansms-sms-reports",
		"vatansms_page_vatansms-wordpress-sms"
	];

	if (!in_array($hook, $pages)) {
		return;
	}


	wp_enqueue_style("app", plugins_url("assets/app.css", __FILE__));
	wp_enqueue_style("font-awesome", "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css");

	// wp_enqueue_script("jquery-new", "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js");
	wp_enqueue_script("app", plugins_url("assets/app.js", __FILE__));
});



// Profile telefon ekleme start
add_filter('user_contactmethods', function ($userContact) {
	$userContact['wp_phone'] = 'Telefon Numarası';
	return $userContact;
});


add_action('user_new_form', function () {
	echo '
      <table class="form-table">
          <tr>
              <th><label for="phone">Telefon Numarası</label></th>
              <td>
                  <input id="phone" type="text" class="regular-text" name="wp_phone" value="" /><br />
              </td>
          </tr>
      </table>';
});


add_action('user_register', function ($userId) {
	update_user_meta($userId, 'wp_phone', sanitize_text_field($_POST['wp_phone']));
}, 10, 1);
// Profile telefon ekleme end



// Woocommerce Hooks start
add_action("woocommerce_new_order", function ($orderId) {
	$order = new WC_Order($orderId);
	$phone = $order->get_billing_phone();
	$fullName = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
	$date = $order->get_date_created();
	$amount = $order->get_total();
	$email = $order->get_billing_email();

	// Müşteriler için
	$smsSendCustomerStatus = get_option("vatansms_wc_create_order_to_customer_status");
	$smsMessageCustomer = get_option("vatansms_wc_create_order_to_customer_message");

	// Adminler için
	$smsSendAdminStatus = get_option("vatansms_wc_create_order_to_numbers_status");
	$smsText = get_option("vatansms_wc_create_order_to_numbers_message");
	$adminPhones = get_option("vatansms_wc_create_order_to_numbers");

	$isLogin = get_option("vatansms_is_login");


	if ($isLogin == 1 && $smsSendCustomerStatus == 1 && strlen($smsMessageCustomer) >= 3) {

		$smsMessageCustomer = str_replace(
			["[ad_soyad]", "[siparis_no]", "[siparis_tutari]", "[siparis_tarihi]", "[email]"],
			[$fullName, $orderId, $amount, date("d-m-Y H:i:s", strtotime($date)), $email],
			$smsMessageCustomer
		);

		SMSHelper::sendSmsOneToN(
			get_option("vatansms_api_id"),
			get_option("vatansms_api_key"),
			get_option("vatansms_sender"),
			$phone,
			$smsMessageCustomer
		);
	}

	if ($isLogin == 1 && $smsSendAdminStatus == 1 && strlen($smsText) > 3) {

		$smsText = str_replace(
			["[ad_soyad]", "[siparis_no]", "[siparis_tutari]", "[siparis_tarihi]", "[email]"],
			[$fullName, $orderId, $amount, date("d-m-Y H:i:s", strtotime($date)), $email],
			$smsText
		);

		SMSHelper::sendSmsOneToN(
			get_option("vatansms_api_id"),
			get_option("vatansms_api_key"),
			get_option("vatansms_sender"),
			$adminPhones,
			$smsText
		);
	}
});


add_action("woocommerce_order_status_cancelled", function ($orderId) {
	$order = new WC_Order($orderId);
	$phone = $order->get_billing_phone();
	$fullName = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
	$date = $order->get_date_created();
	$amount = $order->get_total();
	$email = $order->get_billing_email();

	$isLogin = get_option("vatansms_is_login");
	$smsStatus = get_option("vatansms_wc_cancel_order_to_status");
	$smsText = get_option("vatansms_wc_cancel_order_to_message");

	if ($isLogin == 1 && $smsStatus == 1 && strlen($smsText) > 0) {

		$smsText = str_replace(
			["[ad_soyad]", "[siparis_no]", "[siparis_tutari]", "[siparis_tarihi]", "[email]"],
			[$fullName, $orderId, $amount, date("d-m-Y H:i:s", strtotime($date)), $email],
			$smsText
		);

		SMSHelper::sendSmsOneToN(
			get_option("vatansms_api_id"),
			get_option("vatansms_api_key"),
			get_option("vatansms_sender"),
			$phone,
			$smsText
		);
	}
});


add_action("woocommerce_order_status_completed", function ($orderId) {
	$order = new WC_Order($orderId);
	$phone = $order->get_billing_phone();
	$fullName = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
	$date = $order->get_date_created();
	$amount = $order->get_total();
	$email = $order->get_billing_email();

	$isLogin = get_option("vatansms_is_login");
	$smsStatus = get_option("vatansms_wc_complete_order_to_status");
	$smsText = get_option("vatansms_wc_complete_order_to_message");

	if ($isLogin == 1 && $smsStatus == 1 && strlen($smsText) > 0) {

		$smsText = str_replace(
			["[ad_soyad]", "[siparis_no]", "[siparis_tutari]", "[siparis_tarihi]", "[email]"],
			[$fullName, $orderId, $amount, date("d-m-Y H:i:s", strtotime($date)), $email],
			$smsText
		);

		SMSHelper::sendSmsOneToN(
			get_option("vatansms_api_id"),
			get_option("vatansms_api_key"),
			get_option("vatansms_sender"),
			$phone,
			$smsText
		);
	}
});


add_action("woocommerce_order_status_processing", function ($orderId) {
	$order = new WC_Order($orderId);
	$phone = $order->get_billing_phone();
	$fullName = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
	$date = $order->get_date_created();
	$amount = $order->get_total();
	$email = $order->get_billing_email();

	$isLogin = get_option("vatansms_is_login");
	$smsStatus = get_option("vatansms_wc_prepare_order_to_status");
	$smsText = get_option("vatansms_wc_prepare_order_to_message");

	if ($isLogin == 1 && $smsStatus == 1 && strlen($smsText) > 0) {

		$smsText = str_replace(
			["[ad_soyad]", "[siparis_no]", "[siparis_tutari]", "[siparis_tarihi]", "[email]"],
			[$fullName, $orderId, $amount, date("d-m-Y H:i:s", strtotime($date)), $email],
			$smsText
		);

		SMSHelper::sendSmsOneToN(
			get_option("vatansms_api_id"),
			get_option("vatansms_api_key"),
			get_option("vatansms_sender"),
			$phone,
			$smsText
		);
	}
});


add_action("woocommerce_order_status_on-hold", function ($orderId) {
	$order = new WC_Order($orderId);
	$phone = $order->get_billing_phone();
	$fullName = $order->get_billing_first_name() . " " . $order->get_billing_last_name();
	$date = $order->get_date_created();
	$amount = $order->get_total();
	$email = $order->get_billing_email();

	$isLogin = get_option("vatansms_is_login");
	$smsStatus = get_option("vatansms_wc_on_hold_order_to_status");
	$smsText = get_option("vatansms_wc_on_hold_order_to_message");

	if ($isLogin == 1 && $smsStatus == 1 && strlen($smsText) > 0) {

		$smsText = str_replace(
			["[ad_soyad]", "[siparis_no]", "[siparis_tutari]", "[siparis_tarihi]", "[email]"],
			[$fullName, $orderId, $amount, date("d-m-Y H:i:s", strtotime($date)), $email],
			$smsText
		);

		SMSHelper::sendSmsOneToN(
			get_option("vatansms_api_id"),
			get_option("vatansms_api_key"),
			get_option("vatansms_sender"),
			$phone,
			$smsText
		);
	}
});
// Woocommerce Hooks end


add_action("wpcf7_mail_sent", function ($form) {

	$selectedFormId = get_option("vatansms_contact_form_id");

	if ($form->id() == $selectedFormId) {
		$posts = WPCF7_Submission::get_instance()->get_posted_data();
		$posts_keys = array_map(function ($i) {
			return "[" . $i . "]";
		}, array_keys($posts));

		$posts_value = array_values($posts);


		$isLogin = get_option("vatansms_is_login");

		$adminStatus = get_option("vatansms_contact_form_admin_to_status");
		$adminNumbers = get_option("vatansms_contact_form_admin_to_numbers");
		$adminMsgText = get_option("vatansms_contact_form_admin_to_message");

		$userStatus = get_option("vatansms_contact_form_user_to_status");
		$userMsgText = get_option("vatansms_contact_form_user_to_message");


		if ($isLogin == 1 && isset($posts) && count($posts) > 0) {

			if ($adminStatus == 1 && strlen($adminMsgText) > 3) {


				$adminMsgText = str_replace(
					$posts_keys,
					$posts_value,
					$adminMsgText
				);

				SMSHelper::sendSmsOneToN(
					get_option("vatansms_api_id"),
					get_option("vatansms_api_key"),
					get_option("vatansms_sender"),
					$adminNumbers,
					$adminMsgText
				);
			}

			if ($userStatus == 1 && strlen($userMsgText) > 3 && isset($posts["telephone"])) {

				$userMsgText = str_replace(
					$posts_keys,
					$posts_value,
					$userMsgText
				);

				SMSHelper::sendSmsOneToN(
					get_option("vatansms_api_id"),
					get_option("vatansms_api_key"),
					get_option("vatansms_sender"),
					$posts["telephone"],
					$userMsgText
				);
			}
		}
	}
}, 10, 1);




add_action('wpforms_process_complete', function ($form_data, $fields, $entry, $entry_id) {
    $selectedFormId = get_option('vatansms_wpforms_form_id');
    // Get the telephone field ID specified in plugin settings
    $telephoneFieldId = get_option('vatansms_wpforms_telephone_field_id');

    if ($entry['id'] == $selectedFormId) {
        // Assuming $fields['fields'] contains the field ID and value pairs directly
        $field_values = $fields['fields'];

        $isLogin = get_option("vatansms_is_login");
        $adminStatus = get_option("vatansms_wpforms_admin_to_status");
        $adminNumbers = get_option("vatansms_wpforms_admin_to_numbers");
        $adminMsgText = get_option("vatansms_wpforms_admin_to_message");
        $userStatus = get_option("vatansms_wpforms_user_to_status");
        $userMsgText = get_option("vatansms_wpforms_user_to_message");

        // Sending SMS to Admin
        if ($isLogin == 1 && $adminStatus == 1 && strlen($adminMsgText) > 3) {
            foreach ($field_values as $field_id => $value) {
                $tagName = '[' . $field_id . ']'; // Using field ID as placeholder
                $adminMsgText = str_replace($tagName, $value, $adminMsgText);
            }

            SMSHelper::sendSmsOneToN(
                get_option("vatansms_api_id"),
                get_option("vatansms_api_key"),
                get_option("vatansms_sender"),
                $adminNumbers,
                $adminMsgText
            );
        }

        // Sending SMS to User
        if ($isLogin == 1 && $userStatus == 1 && strlen($userMsgText) > 3 && isset($field_values[$telephoneFieldId])) {
            $telephone = $field_values[$telephoneFieldId]; // Directly get the telephone number using the field ID

            foreach ($field_values as $field_id => $value) {
                $tagName = '[' . $field_id . ']'; // Using field ID as placeholder
                $userMsgText = str_replace($tagName, $value, $userMsgText);
            }

            SMSHelper::sendSmsOneToN(
                get_option("vatansms_api_id"),
                get_option("vatansms_api_key"),
                get_option("vatansms_sender"),
                $telephone,
                $userMsgText
            );
        }
    }
}, 10, 4);







// Admin Ajax
add_action("wp_ajax_get_report_detail", "getReportDetail");
function getReportDetail()
{

	$res = SMSHelper::getSMSReportId(
		get_option("vatansms_api_id"),
		get_option("vatansms_api_key"),
		sanitize_text_field($_POST["report_id"]),
		sanitize_text_field($_POST["page"])
	);
	
	echo json_encode($res);

	wp_die();
}




add_action("user_register", function($user_id) {

	if(
		get_option("vatansms_auth_after_user_message_status_admin") == 1 &&
		strlen(get_option("vatansms_auth_after_user_message_admin")) > 1 &&
		get_option("vatansms_is_login") == 1 &&
		strlen(get_option("vatansms_auth_after_user_message_phone_admin")) == 10
		) {

		$user = get_userdata($user_id);

		$msgText = get_option("vatansms_auth_after_user_message_admin");

		$msgText = str_replace(
			["[kullanici_adi]", "[email]", "[kayit_tarihi]"],
			[$user->user_login, $user->user_email, $user->user_registered],
			$msgText
		);

		SMSHelper::sendSmsOneToN(
			get_option("vatansms_api_id"),
			get_option("vatansms_api_key"),
			get_option("vatansms_sender"),
			get_option("vatansms_auth_after_user_message_phone_admin"),
			$msgText
		);

	}

});
