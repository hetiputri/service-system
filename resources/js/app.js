import "./bootstrap";
import Alpine from "alpinejs";
import ApexCharts from "apexcharts";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

// expose globally
window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
window.flatpickr = flatpickr;

// start Alpine
Alpine.start();

// OPTIONAL: initialize flatpickr auamatically if element exists
document.addEventListener("DOMContentLoaded", () => {
    // Flatpickr init
    const flatpickrElements = document.querySelectorAll("[data-flatpickr]");

    if (flatpickrElements.length > 0) {
        flatpickrElements.forEach((el) => {
            flatpickr(el, {
                dateFormat: "Y-m-d",
                allowInput: true,
            });
        });
    }
});
