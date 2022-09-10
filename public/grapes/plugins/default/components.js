const script = function () {
    const initLib = function () {
        const allSwiper = document.querySelectorAll(".swiper");
        allSwiper.forEach((item) => {
            const className = "." + item.className.replace(" ", ".");
            console.log(className);
            new Swiper(className, {
                loop: true,

                pagination: {
                    el: `${className} .swiper-pagination`,
                },

                navigation: {
                    nextEl: `${className} .swiper-button-next`,
                    prevEl: `${className} .swiper-button-prev`,
                },
            });
        });
    };

    if (typeof Swiper === "undefined") {
        const style = document.createElement("link");
        style.type = "text/css";
        style.href =
            "https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.2.2/swiper-bundle.css";
        const script = document.createElement("script");
        script.src =
            "https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.2.2/swiper-bundle.min.js";
        script.setAttribute("defer", "");
        document.body.appendChild(script);
        document.head.appendChild(style);
        script.onload = initLib;
        noAdd = false;
    } else {
        initLib();
    }
};

export default (editor, opts = {}) => {
    const dc = editor.DomComponents;
    dc.addType(opts.name, {
        model: {
            defaults: {
                script,
            },
        },
        isComponent: (el) => {
            if (el.className && el.className.includes("swiper")) {
                return {
                    type: opts.name,
                };
            }
        },
    });
};
