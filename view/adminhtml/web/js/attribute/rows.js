/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.1.1
 * @since        Class available since Release 1.0.0
 */

define([
    'jquery',
    'mage/template',
    'mage/collapsible',
    'GoMage_Feed/js/attribute/conditions',
    'GoMage_Feed/js/attribute/value',
    'jquery/ui',
    'prototype'
], function (jQuery, mageTemplate, collapsible, Conditions, Value) {
    'use strict';

    return function (config) {
        var Rows = {
            container: $('rows-container'),
            template: mageTemplate('#row-template'),
            count: 0,
            index: 0,
            conditions: [],
            values: [],
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

                this.conditions[this.index] = Conditions({
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
                this.values[this.index] = Value(_data);

                jQuery(this.container).find('.actions-select').collapsible({ "active": false, "animate": 200, "collapsible": true});

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
                    this.values[row_id] = Value({
                        row_id: row_id,
                        type: element.getValue()
                    });
                }
            },
            addCondition: function (event) {
                var element = $(Event.findElement(event, 'button'));
                if (element) {
                    var row_id = element.readAttribute('data-row-id');
                    this.conditions[row_id].add({
                            row_id: row_id
                        }
                    );
                }
            },
            addValue: function (event) {
                var element = $(Event.findElement(event, 'button'));
                if (element) {
                    var row_id = element.readAttribute('data-row-id');
                    if ((typeof this.values[row_id] != 'undefined')) {
                        if (typeof this.values[row_id].object != 'undefined') {
                            this.values[row_id].object.add({
                                    row_id: row_id
                                }
                            );
                        }
                    }
                }
            },
            bindActions: function () {
                Event.observe('add_new_row_button', 'click', this.add.bind(Rows, {}));
                this.container.on('click', '.delete-row', this.remove.bind(this));
                this.container.on('change', '.type-select', this.changeType.bind(this));
                this.container.on('click', '.add-condition', this.addCondition.bind(this));
                this.container.on('click', '.add-value', this.addValue.bind(this));
            }
        };
        Rows.init();
    };
});
