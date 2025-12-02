import * as bootstrap from "bootstrap";
import ApexCharts from "apexcharts";
import Swal from "sweetalert2";

// --- PERBAIKAN DI SINI ---
// 1. Import jQuery DULU
import jQuery from "jquery";

// 2. Baru tetapkan ke Window
window.$ = window.jQuery = jQuery;

// 3. Import Select2 dan jalankan
import select2 from "select2/dist/js/select2.full.min.js";
select2(window, jQuery);
import "select2/dist/css/select2.min.css";

// 4. Import QR Scanner
import { Html5QrcodeScanner } from "html5-qrcode";
window.Html5QrcodeScanner = Html5QrcodeScanner;
// -------------------------

// Import FilePond
import * as FilePond from "filepond";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";

window.ApexCharts = ApexCharts;
window.FilePond = FilePond;

FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType
);

window.FilePondPluginImagePreview = FilePondPluginImagePreview;
window.FilePondPluginFileValidateType = FilePondPluginFileValidateType;

import "./bootstrap";

import { initSidebar } from "./components/sidebar";
import { initTheme } from "./components/dark";

document.addEventListener("DOMContentLoaded", () => {
    initSidebar();
    // initTheme();
});

document.addEventListener("livewire:navigated", () => {
    initSidebar();
    // initTheme();
});
/**
 * =================================================================
 * SweetAlert2 Integration for Livewire Events
 * =================================================================
 */

// Custom listener for toast notifications
window.addEventListener("swal:toast", (event) => {
    Swal.fire({
        toast: true,
        position: "top-end",
        icon: event.detail.type || "success",
        title: event.detail.title || "Done!",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
});

// Custom listener for confirmation modals
window.addEventListener("swal:confirm", (event) => {
    Swal.fire({
        title: event.detail.title || "Are you sure?",
        text: event.detail.text || "You won't be able to revert this!",
        icon: event.detail.icon || "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, do it!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            if (event.detail.onConfirm) {
                // --- PERBAIKAN DI SINI ---
                // Cek apakah params adalah array. Jika bukan, bungkus dalam array [].
                // Ini mencegah error "Only arrays and Traversables can be unpacked" di PHP.
                let params = event.detail.onConfirm.params;
                if (!Array.isArray(params)) {
                    params = [params];
                }
                Livewire.dispatch(
                    event.detail.onConfirm.event,
                    params // Gunakan variabel yang sudah divalidasi
                );
            }
        }
    });
});

// Listener untuk notifikasi sukses
window.addEventListener("event-saved", (event) => {
    Swal.fire({
        toast: true,
        position: "top-end",
        icon: "success",
        title: event.detail.message || "Data berhasil disimpan!",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
});

// Listener untuk notifikasi error
window.addEventListener("error-toast", (event) => {
    Swal.fire({
        toast: true,
        position: "top-end",
        icon: "error",
        title: event.detail.message || "Terjadi kesalahan!",
        showConfirmButton: false,
        timer: 5000, // Waktu lebih lama untuk error
        timerProgressBar: true,
    });
});
