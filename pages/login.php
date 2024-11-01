<?php

if (VSPH::isPost($_POST)) {
    

    if (isset($_POST["is_exit"])) {
        update_option("vatansms_api_id", "");
        update_option("vatansms_api_key", "");
        update_option("vatansms_is_login", "");
		update_option("vatansms_fullname", "");
		update_option("vatansms_kredit", "");

    } else if(isset($_POST["save_sender"])) {
        update_option("vatansms_sender", sanitize_text_field($_POST["select-sender"]));

        VSPH::postState("success", "Gönderici adı başarıyla güncellendi.");

    } else {
        $result = SMSHelper::getUserInformation(sanitize_text_field($_POST["vatansms_api_id"]), sanitize_text_field($_POST["vatansms_api_key"]));

        if ($result["status"] == "success") {

            update_option("vatansms_api_id", sanitize_text_field($_POST["vatansms_api_id"]));
            update_option("vatansms_api_key", sanitize_text_field($_POST["vatansms_api_key"]));

            $senders = SMSHelper::getUserSenders(
                sanitize_text_field($_POST["vatansms_api_id"]),
                sanitize_text_field($_POST["vatansms_api_key"])
            );
//$senders["data"] = 'SMS TEST';
            $successSenders = array_filter($senders["data"], function ($d) {
                if ($d["status"] == 1) {
                    return $d;
                }
            });
//$successSenders = "1";
            if (count($senders["data"]) > 0 && count($successSenders) > 0) {
                update_option("vatansms_is_login", "1");
                VSPH::postState("success", "Api bilgileriniz doğru. Başarıyla giriş yapıldı. Gönderici adınızı seçip eklentiyi kullanmaya başlayabilirsiniz.");
            } else {
                VSPH::postState("error", "Api bilgileriniz doğru. Fakat eklentiyi kullanabilmek için en az bir tane gönderici adına sahip olmalısınız.");
            }
        } else {
            VSPH::postState("error", "Api bilgileriniz yanlış lütfen kontrol ediniz !");
        }
    }
}


if (get_option("vatansms_is_login") == 1) {
    $userInfo = SMSHelper::getUserInformation(get_option("vatansms_api_id"), get_option("vatansms_api_key"))["data"] ?? [];
    $userFullName = $userInfo["name"] . " " . $userInfo["surname"];
    $userCredit = $userInfo["credit"];
	
	update_option("vatansms_fullname", sanitize_text_field($userFullName));
	update_option("vatansms_kredit", sanitize_text_field($userCredit));
	
    $senders = SMSHelper::getUserSenders(
        get_option("vatansms_api_id"),
        get_option("vatansms_api_key")
    );

    
	
	$successSenders = array_filter($senders["data"], function ($d) {
        if ($d["status"] == 1) {
            return $d;
        }
    }); 
	
}
//$senders["data"] = 'SMS TEST';

?>

<style>
    #wpcontent {
        padding-left: 0 !important;
        padding: 0 !important;
    }
</style>


<div class="login">

    <div class="login-form box-shadow">

        <form method="POST" action="" autocomplete="off">

            <div class="d-flex flex-column gap-10">
                <img src="<?php echo vatansms_get_plugin_url() ?>/assets/logo-xl.png" style="width:190px; align-self:center">

                <?php if (get_option("vatansms_is_login") == 1) { ?>
                    <div class="avatar-title" style="margin-bottom:15px;"><i class="far fa-id-card"></i> <?php echo esc_html($userFullName); ?></div>
                    <div class="remaining-credit" style="margin-bottom:15px;">
						<span class="badge-kredi"><i class="fas fa-coins"></i> Kalan Kredi: <?php echo esc_html($userCredit); ?> sms</span>
						<?php if(esc_html($userCredit) <= 100){ ?>
							<a href="https://app.vatansms.net/login"  target="_blank" class="ajib" style="color: #888;"><i class="fas fa-sms"></i> Kredi Yukle</a>
						<?php } ?>
					</div>
                    <div style="display: flex; align-items: center; gap: 5px" style="margin-bottom:15px;">
                        <select class="cs-form" name="select-sender" required>
                            <option value="" selected disabled>Bir gönderici adı seçiniz</option>
                            <?php foreach ($successSenders as $sender) { ?>
                                <option value="<?php echo esc_html($sender["sender"]) ?>" <?php echo (get_option("vatansms_sender") == $sender["sender"]) ? 'selected' : '' ?> ><?php echo esc_html($sender["sender"]) ?></option>
                          <?php } ?>
                        </select>

                        <button class="cs-button" name="save_sender">
                            <i class="far fa-save"></i>
                            Kaydet
                        </button>
                    </div>
                    <small style="margin-bottom:15px;" class="cs-danger">SMS gönderimi yapabilmek için bir gönderici adı seçmelisiniz. Yukarıda sadece onaylanmış gönderici adları listelenir</small>
                    <button name="is_exit" class="cs-button">
                        <i class="fas fa-sign-out-alt"></i>
                        Çıkış Yap
                    </button>
                <?php } else { ?>
                    <input class="cs-form" type="text" name="vatansms_api_id" placeholder="Api id" value="<?php echo esc_html(get_option("vatansms_api_id")) ?>" required>
                    <input class="cs-form" type="text" name="vatansms_api_key" placeholder="Api key" value="<?php echo esc_html(get_option("vatansms_api_key")) ?>" required>
                    <small class="cs-warning">Api id ve api key anahtarlarınızı vatansms.net panelinden alabilirsiniz. <a href="https://app.vatansms.net/login" target="_blank">Vatansms.net Panel <i class="fas fa-external-link-alt"></i></a></small>
                    <button class="cs-button">
                        <i class="fa fa-sign-in"></i>
                        Giriş Yap
                    </button>
                <?php } ?>
				<a href="https://www.vatansms.net/iletisim"  target="_blank" style="color: #888;margin-top:15px;"><i class="fas fa-headset"></i> Yardım Merkezi</a>
            </div>
        </form>
        <?php include(vatansms_get_plugin_path() . "components/message.php"); ?>
    </div>
</div>