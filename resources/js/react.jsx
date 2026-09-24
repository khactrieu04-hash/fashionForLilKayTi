import React from "react";
import { createRoot } from "react-dom/client";

const components = {};

export function registerReactComponent(name, component) {
    components[name] = component;
}

function mountReactComponents() {
    document.querySelectorAll("[data-react-component]").forEach((element) => {
        const component = components[element.dataset.reactComponent];

        if (!component || element.dataset.reactMounted === "true") {
            return;
        }

        const props = element.dataset.reactProps
            ? JSON.parse(element.dataset.reactProps)
            : {};

        createRoot(element).render(React.createElement(component, props));
        element.dataset.reactMounted = "true";
    });
}

document.addEventListener("DOMContentLoaded", mountReactComponents);
