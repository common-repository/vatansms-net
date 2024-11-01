function addTextarea(e) {
    let textarea = jQuery(e).parent().parent().find("textarea");
    let start = textarea[0].selectionStart;
    let end = textarea[0].selectionEnd;
    let newText =
        textarea.val().substring(0, start) +
        jQuery(e).text() +
        textarea.val().substring(end);
    textarea.val(newText);
    textarea.focus();
    textarea[0].selectionEnd = end + jQuery(e).text().length;
}

function hideAlert() {
    if (jQuery(".alert-error, .alert-success").length > 0) {
        setTimeout(() => {
            jQuery(".alert-error, .alert-success").fadeOut();
        }, 5000);
    }
}

function modal(state) {
    if (!state) {
        jQuery(".modal-overlay").removeClass("active");
    } else {
        jQuery(".modal-body").html("");
        jQuery(".modal-overlay").addClass("active");
    }
}

function getReport(reportId, page = 1) {
    loader();

    if (jQuery("#currentReportId").val() != reportId) {
        jQuery("#currentReportId").val(reportId);
    }

    let data = {
        report_id: reportId,
        action: "get_report_detail",
        page: page,
    };

    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: data,
        success: function (response) {
            response = JSON.parse(response);

            if (response.status == "error") {
                alert("Rapor bulunamadı.");
                modal(false);
                return;
            }
            modal(true);

            jQuery(".modal-body").prepend(`<ul class="report-item">`);

            if (response.data.length != 0) {
                if (jQuery(".paginate-select").html().length == 0) {
                    for (let s = 1; s <= response.data.last_page; s++) {
                        jQuery(".paginate-select").append(
                            `<option value="${s}">${s}.</option>`
                        );
                    }
                }

                response.data.data.forEach((element) => {
                    jQuery(".report-item").append(`
                        <li>
                            <b class="mb-7">Gönderilen Telefon Numarası</b> : ${element.phone} <br><br>
                            <b class="mb-7">Mesaj</b> : ${element.message} <br><br>
                            <b class="mb-7">Durum</b> : ${element.status_description} <br><br>
                            <b class="mb-7">Ulaştığı Tarih</b> : ${element.result_time} <br><br>
                            <b class="mb-7">Gönderildiği Tarih</b> : ${element.created_at}
                        </li>
                        <div class="divider"></div>
                    `);
                });
            }

            jQuery(".modal-body").append(`</ul>`);
        },
    });
}

function hideTextarea(e) {
    if (jQuery(e).val() == 0) {
        jQuery(e).parent().find(".w-100").fadeOut();
    } else {
        jQuery(e).parent().find(".w-100").fadeIn();
    }
}

function loader() {
    modal(true);
    jQuery(".modal-body").append(
        `<div class="lds-ring"><div></div><div></div><div></div><div></div></div>`
    );
}


document.addEventListener("DOMContentLoaded", () => {
    hideAlert();
});
