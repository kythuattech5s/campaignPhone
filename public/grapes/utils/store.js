export const storageManager = (id) => {
    return {
        type: "remote",
        autosave: true,
        contentTypeJson: true,
        storeComponents: true,
        storeStyles: true,
        storeHtml: true,
        storeCss: true,
        id: "my-",
        urlStore: `/gp/save-page/${id}`,
        urlLoad: `/gp/load-page/${id}`,
        headers: {
            "Content-Type": "application/json",
        },
    };
};
