/* eslint-disable no-undef */
// jscs:disable jsDoc

define([
    'jquery',
    'mage/template',
    'jquery/ui',
    'prototype'
], function (jQuery, mageTemplate) {
    'use strict';

    return function (config) {
        var Rows = {
            container: $('rows-container'),
            template: mageTemplate('#row-template'),
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
                this.setValues();
                this.index++;
            },
            remove: function (event) {
                var element = $(Event.findElement(event, 'div.fm-block'));
                if (element) {
                    Element.remove(element);
                }
            },
            toggleEdit: function (event) {
                var element = $(Event.findElement(event, 'div.fm-block'));
                if (element) {
                    element.toggleClassName('__opened');
                }
            },
            changeType: function (event) {
                var element = $(Event.findElement(event, 'select'));
                if (element) {
                    this.setValue(element);
                }
            },
            setValues: function () {
                this.container.select('.type-select').forEach(function (typeField) {
                    if (!typeField.readAttribute('data-loaded')) {
                        typeField.writeAttribute('data-loaded', true);
                        this.setValue(typeField);
                    }
                }, this);
            },
            setValue: function (typeField) {
                var elementName = typeField.readAttribute('data-value');
                var input = jQuery("input[name='" + elementName + "'"),
                    select = jQuery("select[name='" + elementName + "'"),
                    values = [];
                switch (parseInt(typeField.getValue())) {
                    case 2:
                        values = [];
                        break;
                    case 8:
                        values = config.dynamicAttributes;
                        break;
                    default:
                        values = config.attributes;
                }

                if (values.length) {
                    input.attr('disabled', 'disabled').hide();
                    select.removeAttr('disabled').show().find('option').remove();
                    values.forEach(function (data) {
                        select.append(jQuery("<option></option>")
                            .attr("value", data.value)
                            .text(data.label));
                    });
                    if (input.val()) {
                        select.val(input.val());
                        input.val('');
                    }
                } else {
                    input.removeAttr('disabled').show();
                    select.attr('disabled', 'disabled').hide();
                }

            },
            bindActions: function () {
                Event.observe('add_new_row_button', 'click', this.add.bind(Rows, {}));
                this.container.on('click', '.delete-row', this.remove.bind(this));
                this.container.on('click', '.edit-row, .close-row', this.toggleEdit.bind(this));

                this.container.on('change', '.type-select', this.changeType.bind(this));
            }
        };
        Rows.init();
    };
});
