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

        var Attribute = {
            template: mageTemplate('#attribute-value-template'),
            count: 0,
            index: 0,
            init: function () {
                value.forEach(function (data) {
                    data.row_id = row_id;
                    this.add(data);
                }, this);
                if (value.length == 0) {
                    this.add({
                        row_id: row_id
                    });
                }
                this.bindActions();
                return this;
            },
            add: function (data) {
                var element;
                data.value_id = this.index;
                element = this.template({
                    data: data
                });
                Element.insert(container, element);
                this.count++;
                this.index++;
            },
            remove: function (event) {
                if (this.count == 1) {
                    return;
                }
                var element = $(Event.findElement(event, 'tr'));
                if (element) {
                    Element.remove(element);
                    this.count--;
                }
            },
            bindActions: function () {
                container.on('click', '.delete-value', this.remove.bind(this));
            }
        };

        return Attribute.init();
    };
});
