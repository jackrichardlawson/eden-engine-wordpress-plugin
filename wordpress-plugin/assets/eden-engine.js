(function () {
    function mountDemo(root) {
        var tabs = root.querySelectorAll("[data-eden-demo-tab]");
        var prompt = root.querySelector("[data-eden-demo-prompt]");
        var details = root.querySelector("[data-eden-demo-details]");

        if (!tabs.length || !prompt || !details) {
            return;
        }

        tabs.forEach(function (tab) {
            tab.addEventListener("click", function () {
                tabs.forEach(function (item) {
                    item.classList.remove("is-active");
                });

                tab.classList.add("is-active");
                prompt.textContent = tab.getAttribute("data-eden-prompt") || "";
                details.textContent = tab.getAttribute("data-eden-details") || "";
            });
        });
    }

    function mountReveal(root) {
        var sections = root.querySelectorAll("[data-eden-engine-section]");

        root.classList.add("eden-engine-js");

        if (!("IntersectionObserver" in window)) {
            sections.forEach(function (section) {
                section.classList.add("is-visible");
            });
            return;
        }

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                });
            },
            { rootMargin: "0px 0px -12% 0px", threshold: 0.08 }
        );

        sections.forEach(function (section) {
            observer.observe(section);
        });
    }

    function mount() {
        document.querySelectorAll(".eden-engine-showcase").forEach(function (root) {
            if (root.dataset.edenEngineMounted === "true") {
                return;
            }

            root.dataset.edenEngineMounted = "true";
            mountDemo(root);
            mountReveal(root);
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", mount, { once: true });
    } else {
        mount();
    }
})();
