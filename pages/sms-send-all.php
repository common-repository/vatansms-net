<?php

$customer = explode(",", vatansms_get_customers_phones());

if (VSPH::isPost($_POST)) {

    if (sanitize_text_field($_POST["to"]) == 1 && is_plugin_active('woocommerce/woocommerce.php')) {
        $numbers = vatansms_get_customers_phones();
    } elseif (sanitize_text_field($_POST["to"]) == 2) {

        $users = get_users(['role__in' => 'subscriber']);
        $numbers = array_map(function ($user) {
            return get_user_meta($user->ID, "wp_phone")[0];
        }, $users);

        $numbers = implode(",", $numbers);
    } elseif (sanitize_text_field($_POST["to"]) == 3) {
        $users = get_users(['role__in' => 'administrator']);
        $numbers = array_map(function ($user) {
            return get_user_meta($user->ID, "wp_phone")[0];
        }, $users);

        $numbers = implode(",", $numbers);
    }


    $res = SMSHelper::sendSmsOneToN(
        get_option("vatansms_api_id"),
        get_option("vatansms_api_key"),
        get_option("vatansms_sender"),
        $numbers,
        sanitize_text_field($_POST["message"])
    );

    if ($res["status"] == "success") {
        VSPH::postState("success", "Smsler başarıyla gönderildi. Toplam gönderilen sms miktarı : " . $res["numberCount"] . " SMS");
    } else {
        VSPH::postState("error", $res["description"]);
    }
}

?>
<?php include_once (vatansms_get_plugin_path() . "pages/top-bar.php"); ?>

<form action="" method="POST">
    <div class="app-amin box-shadow">
        <div class="app-header">SMS Gönder (Toplu)</div>
        <div class="app-content">
            <div class="cs-danger" style="margin-bottom: 20px;">Wordpress üyelerine sms gönderebilmeniz için profil alanında Telefon Numarası alanı dolu olması gereklidir.<br>Woocommerce müşterilerine sms gönderebilmeniz için ise fatura detaylarındaki telefon alanı dolu olması gereklidir.</div>
            <div class="form-group">
                <label>SMS Gönderilecek Gruplar:</label>
                <select name="to" class="cs-form" style="max-width: 100% !important;" required>
                    <option value="" selected disabled>Seçiniz</option>
                    <?php if (is_plugin_active('woocommerce/woocommerce.php')) { ?>
                        <option value="1">Woocommerce Müşterilerine</option>
                    <?php } ?>
                    <option value="2">Wordpress Üyelerine</option>
                    <option value="3">Yöneticilere (Adminlere)</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 30px;">
                <label>Mesajınız:</label>
                <textarea name="message" class="cs-form" style="height: 100px !important;" placeholder="Mesajınız..." required></textarea>
            </div>


            <button class="cs-button mt-10">
                <i class="fas fa-paper-plane"></i>
                Gönder
            </button>

        </div>
    </div>
</form>



<?php include(vatansms_get_plugin_path() . "components/message.php"); ?>
