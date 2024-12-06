import './bootstrap';

import Alpine from 'alpinejs';

import { Html5QrcodeScanner } from "html5-qrcode";

window.Alpine = Alpine;

Alpine.start();

import $ from 'jquery';  // Import jQuery
window.$ = window.jQuery = $;  // Set jQuery globally
