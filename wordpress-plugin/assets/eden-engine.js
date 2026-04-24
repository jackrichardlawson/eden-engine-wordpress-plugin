(function () {
    function mountShowcase(root) {
        if (root.dataset.edenMounted === "true") {
            return;
        }

        root.dataset.edenMounted = "true";
        document.body.classList.add("eden-has-showcase");

        root.querySelectorAll(".eden-media img").forEach(function (image) {
            image.addEventListener(
                "load",
                function () {
                    var frame = image.closest(".eden-media");

                    if (frame) {
                        frame.classList.add("eden-media--loaded");
                    }
                },
                { once: true }
            );

            image.addEventListener(
                "error",
                function () {
                    var frame = image.closest(".eden-media");

                    if (frame) {
                        frame.classList.add("eden-media--fallback");
                    }

                    image.setAttribute("aria-hidden", "true");
                },
                { once: true }
            );

            if (image.complete && image.naturalWidth > 0) {
                var frame = image.closest(".eden-media");

                if (frame) {
                    if (image.naturalWidth <= 2 && image.naturalHeight <= 2) {
                        frame.classList.add("eden-media--fallback");
                        image.setAttribute("aria-hidden", "true");
                    } else {
                        frame.classList.add("eden-media--loaded");
                    }
                }
            }
        });
    }

    function mount() {
        document.querySelectorAll(".eden-showcase").forEach(mountShowcase);
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", mount, { once: true });
    } else {
        mount();
    }
})();
