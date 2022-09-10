import loadComponents from "./default/components.js";
import loadBlocks from "./default/block.js";

export default grapesjs.plugins.add("my-swiper", (editor, opts) => {
    let options = {
        label: "Swiper",
        name: "cswiper",
        category: "Custom",
    };

    for (let name in options) {
        if (!(name in opts)) opts[name] = options[name];
    }

    loadBlocks(editor, opts);
    loadComponents(editor, opts);
});