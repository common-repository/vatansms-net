<?php




$res = SMSHelper::getSMSReportDate(
    get_option("vatansms_api_id"),
    get_option("vatansms_api_key"),
    (isset($_POST["start_date"]) ? sanitize_text_field($_POST["start_date"]) . " 00:00:00" : date("Y-m-d H:i:s", strtotime("-1 week"))),
    (isset($_POST["end_date"]) ? sanitize_text_field($_POST["end_date"]) . " 23:59:59" : date("Y-m-d H:i:s"))
);

?>


<div class="modal-overlay">
    <div class="modal">
        
    <div class="d-flex align-center gap-5">
        <select class="cs-form paginate-select" onchange="getReport(jQuery(`#currentReportId`).val(), jQuery(this).val())"></select>
        <span>Sayfaya git</span>
    </div>
    
        <div class="modal-body">
            <ul class="report-item">

            </ul>
        </div>

        <button class="cs-button" onclick="modal(false); jQuery(`.paginate-select`).html(``)" style="float: right;">Kapat</button>
    </div>
</div>

<?php include_once (vatansms_get_plugin_path() . "pages/top-bar.php"); ?>

<div class="app-amin box-shadow">
    <div class="app-header">SMS Raporları</div>
    <div class="app-content">
        <form action="" method="POST">

            <div class="d-flex" style="gap: 20px; align-items: flex-end;">

                <div class="form-group">
                    <label>Başlangıç Tarihi</label>
                    <input name="start_date" value="<?php echo (isset($_POST["start_date"])) ? esc_html($_POST["start_date"]) : date("Y-m-d", strtotime("-1 week")) ?>" type="date" class="cs-form" required>
                </div>

                <div class="form-group">
                    <label>Bitiş Tarihi</label>
                    <input name="end_date" value="<?php echo (isset($_POST["start_date"])) ? esc_html($_POST["end_date"]) :  date("Y-m-d") ?>" type="date" class="cs-form" required>
                </div>

                <button class="cs-button" name="filterBtn" type="submit" style="max-height: 45px;">Filtrele</button>

            </div>
        </form>


        <table class="table table-bordered" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Mesaj</th>
                    <th>Gönderici Adı</th>
                    <th>Gönderim Sonrası Kalan SMS</th>
                    <th>Gönderilen SMS Adeti</th>
                    <th>Detay</th>
                </tr>
            </thead>

            <tbody>
                <?php if (get_option("vatansms_is_login") == 1) { ?>
                    <?php if (count($res["data"]) == 0) { ?>
                        <div class="cs-danger" style="margin-top: 20px;">
                            Aradığınız sonuç bulunamadı.
                        </div>
                    <?php } else { ?>
                        <?php foreach ($res["data"] as $r) { ?>
                            <tr>
                                <td><b><?php echo esc_html($r["id"]) ?></b></td>
                                <td><?php echo esc_html($r["message"]) ?></td>
                                <td><?php echo esc_html($r["sender"]) ?></td>
                                <td><?php echo esc_html($r["credit_before"]) ?></td>
                                <td><?php echo esc_html($r["count"]) ?></td>
                                <td><a style="cursor: pointer;" onclick="getReport('<?php echo esc_html($r['id']) ?>')">Detay Görüntüle</a></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>



    </div>
</div>

<input type="hidden" id="currentReportId">


<?php include(vatansms_get_plugin_path() . "components/message.php"); ?>