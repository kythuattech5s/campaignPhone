export default {
    defaults: [{
            id: "basic-actions",
            el: ".panel__basic-actions",
            buttons: [{
                    id: "visibility",
                    active: true,
                    className: "fa-solid fa-border-none",
                    command: "sw-visibility",
                    attributes: {
                        title: "View components"
                    },
                },
                {
                    id: "preview",
                    className: "fa fa-eye",
                    command: "preview",
                    context: "preview",
                    attributes: {
                        title: "Preview"
                    },
                },
                {
                    id: "fullscreen",
                    className: "fa fa-arrows-alt",
                    command: "fullscreen",
                    context: "fullscreen",
                    attributes: {
                        title: "Fullscreen"
                    },
                },
                {
                    id: "saveDb",
                    className: "fa-solid fa-download",
                    command: "saveDb",
                    attributes: {
                        title: "Save"
                    },
                },
                {
                    id: "cmd-clear",
                    className: "fa-solid fa-trash-can",
                    command: "cmd-clear",
                    attributes: {
                        title: "Clear"
                    },
                },
                {
                    id: "undo",
                    className: "fa-solid fa-rotate-left",
                    command: "undo",
                    attributes: {
                        title: "Undo"
                    },
                },
                {
                    id: "redo",
                    className: "fa-solid fa-rotate-right",
                    command: "redo",
                    attributes: {
                        title: "Redo"
                    },
                },
            ],
        },
        {
            id: "panel-devices",
            el: ".panel__devices",
            buttons: [{
                    id: "device-desktop",
                    label: `<i class="fa-solid fa-display"></i>`,
                    command: "set-device-desktop",
                    active: true,
                    togglable: false,
                    attributes: {
                        title: "Desktop"
                    },
                },
                {
                    id: "device-mobile",
                    label: `<i class="fa-solid fa-mobile-screen"></i>`,
                    command: "set-device-mobile",
                    attributes: {
                        title: "Mobile"
                    },
                },
            ],
        },
        {
            id: "panel-style",
            el: ".panel__show_styles",
            buttons: [{
                    id: "show-action",
                    label: `<i class="fa fa-sliders" aria-hidden="true"></i>`,
                    command: "show-action-class",
                    attributes: {
                        title: "Show Action"
                    },
                },
                {
                    id: "show-classess",
                    label: `<i class="fa fa-th-large" aria-hidden="true"></i>`,
                    command: "show-custom-class",
                    attributes: {
                        title: "Add Class"
                    },
                }
            ],
        },
        {
            id: "panel-classess",
            buttons: [{
                id: "open-sm",
                className: "fa fa-paint-brush",
                command: "open-sm",
                active: true,
                togglable: 0,
                attributes: {
                    title: "Open Style Manager"
                },
            }, ],
        },
    ],
};
