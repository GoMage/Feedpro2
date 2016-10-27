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
        var Configurable = {
            template: mageTemplate('#configurable-value-template'),
            init: function () {
                var element, data;
                data = {
                    row_id: row_id,
                    prefix: (typeof value.prefix == 'undefined') ? '' : value.prefix,
                    code: (typeof value.code == 'undefined') ? '' : value.code
                };
                element = this.template({
                    data: data
                });
                Element.insert(container, element);
            }
        };
        Configurable.init();
    };
});
