/* eslint-disable no-undef */
// jscs:disable jsDoc

define([
    'jquery',
    'mage/template',
    'mage/translate',
    'jquery/ui',
    'prototype'
], function (jQuery, mageTemplate, translate) {
    'use strict';

    return function (config) {
        var Rows = {
            container: $('rows-container'),
            template: mageTemplate('#row-template'),
            titleTemplate: mageTemplate('#title-template'),
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
                this.setTitle(data.row_id);
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
                    var row_id = element.readAttribute('data-row-id');
                    this.setTitle(row_id);
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
                    select.append(jQuery("<option></option>")
                        .attr("value", "")
                        .text(translate("Not Set")));
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
            setTitle: function (row_id) {
                var data = {},
                    element,
                    container = $('title-container-' + row_id);

                data.name = jQuery("input[name='" + config.htmlName + "[" + row_id + "][name]'").val();
                if (!data.name) {
                    data.name = translate('New Row');
                }

                data.type = jQuery("select[name='" + config.htmlName + "[" + row_id + "][type]'").find('option:selected').text();
                data.value = jQuery("select[name='" + config.htmlName + "[" + row_id + "][value]'").is(':disabled') ?
                    jQuery("input[name='" + config.htmlName + "[" + row_id + "][value]'").val() :
                    jQuery("select[name='" + config.htmlName + "[" + row_id + "][value]'").find('option:selected').text();

                data.prefix_type = jQuery("select[name='" + config.htmlName + "[" + row_id + "][prefix_type]'").find('option:selected').text();
                data.prefix_value = jQuery("select[name='" + config.htmlName + "[" + row_id + "][prefix_value]'").is(':disabled') ?
                    jQuery("input[name='" + config.htmlName + "[" + row_id + "][prefix_value]'").val() :
                    jQuery("select[name='" + config.htmlName + "[" + row_id + "][prefix_value]'").find('option:selected').text();

                data.suffix_type = jQuery("select[name='" + config.htmlName + "[" + row_id + "][suffix_type]'").find('option:selected').text();
                data.suffix_value = jQuery("select[name='" + config.htmlName + "[" + row_id + "][suffix_value]'").is(':disabled') ?
                    jQuery("input[name='" + config.htmlName + "[" + row_id + "][suffix_value]'").val() :
                    jQuery("select[name='" + config.htmlName + "[" + row_id + "][suffix_value]'").find('option:selected').text();

                element = this.titleTemplate({
                    data: data
                });

                container.innerHTML = '';
                Element.insert(container, element);
            },
            bindActions: function () {
                Event.observe('add_new_row_button', 'click', this.add.bind(Rows, {}));
                this.container.on('click', '.delete-row', this.remove.bind(this));
                this.container.on('click', '.edit-row, .close-row', this.toggleEdit.bind(this));

                this.container.on('change', '.type-select', this.changeType.bind(this));
            }
        };
        Rows.init();
        jQuery(function ($) {
            $('[data-role=rows-container]').sortable({
                distance: 8,
                tolerance: 'pointer',
                cancel: 'input, button, select',
                axis: 'y',
                update: function () {
                    $('[data-role=rows-container] [data-role=order]').each(function (index, element) {
                        $(element).val(index + 1);
                    });
                }
            });
        });
    };
});
