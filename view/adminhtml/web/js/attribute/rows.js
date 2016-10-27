/* eslint-disable no-undef */
// jscs:disable jsDoc

define([
    'jquery',
    'mage/template',
    'GoMage_Feed/js/attribute/conditions',
    'GoMage_Feed/js/attribute/value',
    'jquery/ui',
    'prototype'
], function (jQuery, mageTemplate, Conditions, Value) {
    'use strict';

    return function (config) {
        var Rows = {
            container: $('rows-container'),
            template: mageTemplate('#row-template'),
            count: 0,
            index: 0,
            init: function () {
                config.rowsData.forEach(function (data) {
                    this.add(data);
                }, this);
                if (config.rowsData.length == 0) {
                    this.add({});
                }
                this.bindActions();
            },
            add: function (data) {
                var element;
                data.row_id = this.index;
                element = this.template({
                    data: data
                });
                Element.insert(this.container, element);

                Conditions({
                    url: config.url,
                    row_id: this.index,
                    conditionsData: (typeof data.conditions == 'undefined') ? [] : data.conditions
                });

                var _data = {
                    row_id: this.index,
                    type: (typeof data.type == 'undefined') ? 2 : data.type
                };
                if (typeof data.value != 'undefined') {
                    _data.value = data.value;
                }
                Value(_data);

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
            changeType: function (event) {
                var element = $(Event.findElement(event, 'select'));
                if (element) {
                    var row_id = element.readAttribute('data-row-id');
                    Value({
                        row_id: row_id,
                        type: element.getValue()
                    });
                }
            },
            bindActions: function () {
                Event.observe('add_new_row_button', 'click', this.add.bind(Rows, {}));
                this.container.on('click', '.delete-row', this.remove.bind(this));
                this.container.on('change', '.type-select', this.changeType.bind(this));
            }
        };
        Rows.init();
    };
});
