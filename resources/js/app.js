import * as bootstrap from 'bootstrap';
import jQuery from 'jquery';
import '@popperjs/core';
import 'select2';
import 'select2/dist/css/select2.css';

window.$ = window.jQuery = jQuery;

// Initialize Bootstrap components
document.addEventListener('DOMContentLoaded', () => {
    // Enable tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Enable popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Enable dropdowns
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Initialize Select2
    $('.form-select').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dir: 'rtl',
        language: {
            errorLoading: function () {
                return 'لا يمكن تحميل النتائج';
            },
            inputTooLong: function (args) {
                return 'الرجاء حذف ' + (args.input.length - args.maximum) + ' حرف';
            },
            inputTooShort: function (args) {
                return 'الرجاء إضافة ' + (args.minimum - args.input.length) + ' حرف أو أكثر';
            },
            loadingMore: function () {
                return 'جاري تحميل نتائج إضافية...';
            },
            maximumSelected: function (args) {
                return 'تستطيع إختيار ' + args.maximum + ' بنود فقط';
            },
            noResults: function () {
                return 'لم يتم العثور على أي نتائج';
            },
            searching: function () {
                return 'جاري البحث…';
            }
        }
    });

    // Global Select2 initialization
    $('select:not(.no-select2)').select2({
        theme: 'bootstrap-5',
        width: '100%',
        templateResult: formatOption,
        templateSelection: formatOption,
    });

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
});
