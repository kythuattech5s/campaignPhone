$(function () {
    $(".datepicker").datetimepicker({
        format: "d/m/Y H:i:s",
        formatTime: "H:i:s",
        formatDate: "d/m/Y",
        onClose: function (time, input, event) {
            var val = $(input).val();
            if (val != undefined && val != "") {
                var p = val.split(" ");
                var d = p[0].split("/");
                var t = p[1].split(":");
                var ret = d[2] + "-" + d[1] + "-" + d[0] + " " + p[1];
                if ($(input).attr("dt-type") == "INTDATETIME") {
                    var date = new Date(
                        d[2],
                        parseInt(d[1]) - 1,
                        d[0],
                        t[0],
                        t[1],
                        t[2]
                    );
                    $(input)
                        .next()
                        .val(date.getTime() / 1000);
                } else {
                    $(input).next().val(ret);
                }
            }
        },
    });
    $(".datepicker-filter").datetimepicker({
        format: "d/m/Y H:i:s",
        formatTime: "H:i:s",
        formatDate: "d/m/Y",
    });
    $(".boxtitle .btnshow").click(function (event) {
        var boxedit = $(this).closest(".boxedit");
        var boxhide = boxedit.find(".boxhide");
        if (boxhide.is(":visible")) {
            boxhide.slideUp();
        } else {
            boxhide.slideDown();
        }
    });
    $("._vh_update").click(function (event) {
        event.preventDefault();
        if (!validateForm("#frmUpdate")) {
            return;
        }
        try {
            tinyMCE.triggerSave();
        } catch (e) {}
        $("#frmUpdate").attr("action", $("#frmUpdate").attr("dt-ajax"));
        var frmClone = $("#frmUpdate").clone();
        frmClone.ajaxForm();
        frmClone.submit();
        frmClone.remove();
    });
    $("._vh_save").click(function (event) {
        event.preventDefault();
        if (!validateForm("#frmUpdate")) {
            return;
        }
        try {
            tinyMCE.triggerSave();
        } catch (e) {}
        $("#frmUpdate").attr("action", $("#frmUpdate").attr("dt-normal"));
        setTimeout(function () {
            /*if(window.location.pathname.indexOf('esystem/edit/news/') !== -1){
                DRAFT.clickSaveHistory('save');
            }else{*/
            $("#frmUpdate").submit();
            /* }*/
        }, 300);
    });

    $(".save_draft").click(function (event) {
        event.preventDefault();
        try {
            tinyMCE.triggerSave();
        } catch (e) {}
        $("#frmUpdate").attr("action", $("#frmUpdate").attr("dt-normal"));
        $("#frmUpdate").append('<input name="is_draft" value="1">');
        DRAFT.clickSaveHistory("save");
        $("#frmUpdate").submit();
    });
    $(function () {
        $(".iframe-btn").fancybox({
            width: "75%",
            height: "75%",
            autoScale: false,
            transitionIn: "none",
            transitionOut: "none",
            type: "iframe",
        });
    });
    $(function () {
        $(".fancybox").fancybox();
    });

    function validateForm(frm) {
        var arr = $(frm).find(
            "input[required],select[required],textarea[required]"
        );
        for (var i = 0; i < arr.length; i++) {
            var item = $(arr[i]);
            var val = item.val();
            if (val == undefined || val == "") {
                item.focus();
                $.simplyToast(
                    "Vui lòng nhập " + $(item).attr("placeholder"),
                    "danger"
                );
                return false;
            }
        }
        return true;
    }

    if (
        $('select[name="province_id"]').length > 0 &&
        $('select[name="district_id"]').length > 0
    ) {
        $('select[name="district_id"]').attr("disabled", true);
        $(document).on("change", 'select[name="province_id"]', function (e) {
            var _this = $(this);

            var id = $(this).val();
            $.ajax({
                url: "esystem/get-district-from-province",
                type: "POST",
                data: {
                    province_id: id,
                },
                beforeSend: function () {
                    _this.attr("disabled", true);
                    if (document.querySelector('select[name="ward_id"]')) {
                        document.querySelector(
                            'select[name="ward_id"]'
                        ).innerHTML =
                            '<option value="">-- Phường/ Xã --</option>';
                    }

                    if (document.querySelector('select[name="district_id"]')) {
                        document.querySelector(
                            'select[name="district_id"]'
                        ).innerHTML =
                            '<option value="">-- Quận/ Huyện --</option>';
                    }
                    $('select[name="district_id"]').attr("disabled", true);
                },
            })
                .done(function (res) {
                    $('select[name="district_id"]').html(res.html);
                    _this.attr("disabled", false);
                    $('select[name="district_id"]').attr("disabled", false);
                })
                .error(function (res) {
                    _this.attr("disabled", false);
                });
        });
    }

    if (
        $('select[name="district_id"]').length > 0 &&
        $('select[name="ward_id"]').length > 0
    ) {
        $('select[name="ward_id"]').attr("disabled", true);
        $(document).on("change", 'select[name="district_id"]', function (e) {
            var _this = $(this);

            var id = $(this).val();
            $.ajax({
                url: "esystem/getDataWardByDistrict",
                type: "POST",
                data: {
                    district_id: id,
                },
                beforeSend: function () {
                    _this.attr("disabled", true);
                    if (document.querySelector('select[name="ward_id"]')) {
                        document.querySelector(
                            'select[name="ward_id"]'
                        ).innerHTML =
                            '<option value="">-- Phường/ Xã --</option>';
                    }
                    $('select[name="ward_id"]').attr("disabled", true);
                },
            })
                .done(function (res) {
                    $('select[name="ward_id"]').html(res.html);
                    $('select[name="ward_id"]').attr("disabled", false);
                    _this.attr("disabled", false);
                })
                .error(function (res) {
                    _this.attr("disabled", false);
                });
        });
    }
});
