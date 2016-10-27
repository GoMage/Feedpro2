/* eslint-disable no-undef */
// jscs:disable jsDoc

define([
    'jquery',
    'mage/template',
    'jquery/ui',
    'prototype'
], function (jQuery, mageTemplate) {
    'use strict';

    return function (container, row_id, value) {
        var Percent = {
            template: mageTemplate('#percent-value-template'),
            init: function () {
                var element, data;
                data = {
                    row_id: row_id,
                    percent: (typeof value.percent == 'undefined') ? '' : value.percent,
                    code: (typeof value.code == 'undefined') ? '' : value.code
                };
                element = this.template({
                    data: data
                });
                Element.insert(container, element);
            }
        };
        Percent.init();
    };
});
