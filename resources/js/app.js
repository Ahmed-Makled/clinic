import * as bootstrap from 'bootstrap';
import jQuery from 'jquery';
import '@popperjs/core';
import 'select2';
import 'select2/dist/css/select2.css';

window.$ = window.jQuery = jQuery;

// Wait for jQuery to be properly loaded
jQuery(function ($) {


    // Format option with icon if available
    function formatOption(option) {
        if (!option.id) return option.text;

        const icon = $(option.element).data('icon');
        if (!icon) return option.text;

        const color = $(option.element).data('color') || '#495057';

        return $(`
            <div class="select2-option">
                <i class="bi ${icon}" style="color: ${color}"></i>
                <span>${option.text}</span>
            </div>
        `);
    }

    // Initialize Bootstrap components
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
