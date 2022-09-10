"use strict";
var TABLE_VIEW = (function () {
    var getLink = function () {
        if ($("button.get-link").length == 0) return;
        $("button.get-link").unbind("click");
        $("button.get-link").click(function () {
            copyLink($(this).prev());
        });

        function copyLink(element) {
            var textarea = document.createElement("textarea");
            textarea.textContent =
                window.location.origin + "/" + $(element).val();
            textarea.style.height = 0;
            textarea.style.width = 0;
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, 99999);
            document.execCommand("copy");
            document.body.removeChild(textarea);
            $.simplyToast("Đã sao chép", "success");
        }
    };

    var clickSort = function () {
        const table = document.querySelector(".main_table table");
        if (!table) return;
        const datas = table.querySelectorAll(".cursor-pointer");

        datas.forEach(function (el) {
            el.onclick = (e) => (window.location.href = el.dataset.href);
        });
    };

    var selectSort = function () {
        const selects = document.getElementsByClassName(
            "filter-table__bottom select"
        );
        if (selects.length == 0) return;
        selects.forEach(function (el) {
            el.onchange = function () {
                el.closest("form").submit();
            };
        });
    };

    var buttonSort = function () {
        const sortBtn = document.querySelector(".filter-table__sort-title");
        if (!sortBtn) return;
        sortBtn.onclick = function () {
            sortBtn.closest("form").submit();
        };
    };

    return {
        _: function () {
            getLink();
            clickSort();
            selectSort();
            buttonSort();
        },
    };
})();

// Kiểm tra bảng
var TABLE_CHECK = (function () {
    var getAllConfig = function () {
        const getAllConfig = document.querySelectorAll("[sys-config]");
        if (getAllConfig.length == 0) return;
        getAllConfig.forEach((configEl) => {
            const type = configEl.dataset.type;
            switch (type) {
                case "checkExit":
                    return checkExit(configEl);
                    break;
                case "checkEdit":
                    return checkEdit(configEl);
                    break;
                case "":
                    break;
            }
        });
    };

    var checkExit = function (configEl) {
        if (configEl.dataset.action === "insert") return;
        window.addEventListener("unload", function (event) {
            console.log("unload now");
            const action = configEl.dataset.action;
            if (action === "insert") return;
            const table = configEl.dataset.table;
            const id = document.querySelector(".one.hidden");
            updateTimeEditing(action, table, id, "sys-check-table/remove-editing");
        });



        
        window.addEventListener("beforeunload", (e) =>
            beforeUnload(e, configEl)
        );
    };
    var beforeUnload = function (e, configEl) {
        e.preventDefault();
        e.returnValue = "";
    };

    var checkEdit = function (configEl) {
        const action = configEl.dataset.action;
        const timeout = configEl.dataset.time;
        const table = configEl.dataset.table;
        if (action === "insert") return;
        const id = document.querySelector(".one.hidden");
        updateTimeEditing(action, table, id, "sys-check-table/check-editing");
        setInterval(() => {
            updateTimeEditing(
                action,
                table,
                id,
                "sys-check-table/check-editing"
            );
        }, timeout);
    };

    var updateTimeEditing = function (action, table, id, url) {
        $.ajax({
            url: url,
            type: "POST",
            data: {
                table,
                action,
                id: id.getAttribute("dt-id"),
            },
        });
    };
    return {
        _: () => {
            getAllConfig();
        },
    };
})();

window.addEventListener("DOMContentLoaded", function () {
    TABLE_VIEW._();
    TABLE_CHECK._();
});
