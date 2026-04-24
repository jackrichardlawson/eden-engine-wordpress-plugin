(function () {
    function updateTargetMapper(root) {
        var efficiencyInput = root.querySelector("[data-eden-efficiency]");
        var electricityInput = root.querySelector("[data-eden-electricity]");

        if (!efficiencyInput || !electricityInput) {
            return;
        }

        var efficiencyOutput = root.querySelector("[data-eden-efficiency-output]");
        var electricityOutput = root.querySelector("[data-eden-electricity-output]");
        var modeledCost = root.querySelector("[data-eden-modeled-cost]");
        var energyIntensity = root.querySelector("[data-eden-energy-intensity]");
        var energyShare = root.querySelector("[data-eden-energy-share]");
        var outputIndex = root.querySelector("[data-eden-output-index]");

        function render() {
            var efficiency = Number(efficiencyInput.value);
            var electricity = Number(electricityInput.value);
            var intensity = 39.9 * (82 / efficiency);
            var energy = intensity * electricity;
            var cost = Math.max(14, 310 * Math.pow(70 / efficiency, 2.25) + energy - Math.max(0, efficiency - 70) * 2.1);
            var output = 0.78 * Math.pow(efficiency / 70, 1.6);

            efficiencyOutput.textContent = efficiency + "%";
            electricityOutput.textContent = "$" + electricity.toFixed(2) + "/kWh";
            modeledCost.textContent = "$" + cost.toFixed(0) + "/kg";
            energyIntensity.textContent = intensity.toFixed(1) + " kWh/kg";
            energyShare.textContent = "$" + energy.toFixed(2) + "/kg";
            outputIndex.textContent = output.toFixed(2) + "x";
        }

        efficiencyInput.addEventListener("input", render);
        electricityInput.addEventListener("input", render);
        render();
    }

    function updatePathwayDemo(root) {
        var buttons = root.querySelectorAll("[data-eden-route]");
        var confidence = root.querySelector("[data-eden-route-confidence]");
        var details = root.querySelector("[data-eden-route-details]");
        var ring = root.querySelector(".eden-ring");

        if (!buttons.length || !confidence || !details || !ring) {
            return;
        }

        buttons.forEach(function (button) {
            button.addEventListener("click", function () {
                buttons.forEach(function (item) {
                    item.classList.remove("is-active");
                });

                button.classList.add("is-active");
                confidence.textContent = button.dataset.confidence || "";
                details.textContent = button.dataset.details || "";
                ring.style.setProperty("--eden-ring", button.dataset.confidence || "0");
            });
        });
    }

    function mount() {
        document.querySelectorAll(".eden-showcase").forEach(function (root) {
            if (root.dataset.edenMounted === "true") {
                return;
            }

            root.dataset.edenMounted = "true";
            document.body.classList.add("eden-has-showcase");
            updateTargetMapper(root);
            updatePathwayDemo(root);
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", mount, { once: true });
    } else {
        mount();
    }
})();
