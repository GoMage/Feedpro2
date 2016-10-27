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
        var Static = {
            template: mageTemplate('#static-value-template'),
            init: function () {
                var element, data;
                data = {
                    row_id: row_id,
                    value: value
                };
                element = this.template({
                    data: data
                });
                Element.insert(container, element);
            }
        };
        Static.init();
    };
});
