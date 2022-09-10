import mySwiper from "./plugins/swiper.js";
import blockManager from "./utils/block.js";
import styleManager from "./utils/style.js";
import layerManager from "./utils/layer.js";
import traitManager from "./utils/trait.js";
import { storageManager } from "./utils/store.js";
import panelConfig from "./utils/panelConfig.js";
import { buttonElement } from "./utils/buttonAction.js";
const id = document.body.dataset.id;
const editor = grapesjs.init({
    container: "#editor",
    width: "100%",
    storageManager: storageManager(id),
    avoidInlineStyle: 1,
    dragMode: 'absolute',
    height:"10000px",
    showOffsets: 1,
    canvas: {
        styles: [
            "https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.2.2/swiper-bundle.css",
        ],
        scripts: [
            "https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.2.2/swiper-bundle.min.js",
        ],
    },
    //Cấu hình ở đây

    blockManager: blockManager,
    layerManager: layerManager,
    styleManager: styleManager,
    traitManager: traitManager,
    panels: panelConfig,
    
    deviceManager: {
        devices: [
            {
                name: "Desktop",
                width: "1920px",
            },
            {
                name: "Mobile",
                width: "375px",
                widthMedia: "480px",
            },
        ],
    },
    plugins: ["gjs-blocks-basic", mySwiper],
    pluginOpts: {
        "gjs-blocks-basic": {},
    },
});

buttonElement(editor);
