export const buttonElement = (editor) => {
    // Commands
    editor.Commands.add("set-device-desktop", {
        run: (editor) => editor.setDevice("Desktop"),
    });

    editor.Commands.add("set-device-mobile", {
        run: (editor) => editor.setDevice("Mobile"),
    });

    editor.on("component:selected", function (model) {
        if (model.attributes.type === "image") {
            model.set("dmode", "absolute");
        }
    });

    // Save Button
    editor.Commands.add("saveDb", {
        run: (editor, sender) => {
            sender && sender.set("active");
            editor.store();
            editor.runCommand("sw-visibility");
        },
    });

    //Clear Button
    editor.Commands.add("cmd-clear", {
        run: (editor) => {
            editor.DomComponents.clear();
            editor.CssComposer.clear();
            editor.runCommand("sw-visibility");
        },
    });

    //Undo
    editor.Commands.add("undo", {
        run: (editor) => {
            editor.UndoManager.undo(), editor.runCommand("sw-visibility");
        },
    });

    // Redo
    editor.Commands.add("redo", {
        run: (editor) => {
            console.log(editor);
            editor.UndoManager.redo(), editor.runCommand("sw-visibility");
        },
    });

    //Show Slide
    editor.Commands.add("show-custom-class", {
        run: (editor) => {
            document
                .querySelector(".gjs-pn-views-container")
                .classList.toggle("show");
        },
    });

    //Show Slide
    editor.Commands.add("show-custom-class", {
        run: (editor) => {
            document
                .querySelector(".gjs-pn-views-container")
                .classList.toggle("show");
        },
    });

    //Show Slide
    editor.Commands.add("show-action-class", {
        run: (editor) => {
            document
                .querySelector(".sidenav")
                .classList.toggle("show");
        },
    });

    
};
