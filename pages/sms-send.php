<?php



if (VSPH::isPost($_POST)) {


    $res = SMSHelper::sendSmsOneToN(
        get_option("vatansms_api_id"),
        get_option("vatansms_api_key"),
        get_option("vatansms_sender"),
        sanitize_text_field($_POST["phones"]),
        sanitize_text_field($_POST["message"])
    );

    if ($res["status"] == "success") {
        VSPH::postState("success", "Sms başarıyla gönderildi.");
    } else {
        VSPH::postState("error", $res["description"]);
    }
}

?>
<?php include_once (vatansms_get_plugin_path() . "pages/top-bar.php"); ?>

<form action="" method="POST">
    <div class="app-amin box-shadow">
        <div class="app-header">SMS Gönder</div>
        <div class="app-content">

            <div class="form-group">
                <label>SMS Gönderilecek Telefonlar :</label>
                <textarea name="phones" class="cs-form" style="height: 100px !important;" placeholder="Telefon numaralarını girin..." required></textarea>
                <small class="cs-danger">Birden fazla numara girecekseniz numaraları virgül(,) ile ayırınız. Örneğin; 5555555555,5555555555,5555555555</small>
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